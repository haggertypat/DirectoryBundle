<?php
namespace CCETC\DirectoryBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class NotificationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ccetc:directory:send-upcoming-expiration-notifications')
            ->setDescription('send notifications to listing owners with an upcoming listing expiration')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $listingTypeHelper = $this->getContainer()->get('ccetc.directory.helper.listingtypehelper');

        foreach($listingTypeHelper->getAll() as $listingType) {
            $notificationsSentCount = 0;
            $listingAdmin = $listingType->getAdminClass();
            $listings = $listingType->getRepository()->findAll();

            foreach($listings as $listing)
            {
                $timeValue = null; // need to reset to null, or it's still set next time through the loop
                $timeLabel = null;

                if($listing->expiringInExactlyOneWeek()) {
                    $timeValue = 1;
                    $timeLabel = "week";
                } else if($listing->expiringInExactlyTwoWeeks()) {
                    $timeValue = 2;
                    $timeLabel = "week";
                } else if($listing->expiringInExactlyOneDay()) {
                    $timeValue = 1;
                    $timeLabel = "day";                    
                }

                if(isset($timeValue)) {
                    $this->sendNotification($listing, $timeValue, $timeLabel);
                    $notificationsSentCount++;
                }
            }

            $output->writeln($notificationsSentCount.' notifications sent to Listing owners of type "'.$listingType->getKey().'"');
        }
    }

    protected function sendNotification($listing, $timeValue, $timeLabel)
    {
        $directoryTitle = $this->getContainer()->getParameter('ccetc_directory.title');
        $listingTypeHelper = $this->getContainer()->get('ccetc.directory.helper.listingtypehelper');
        $listingType = $listingTypeHelper->findOneByEntityClassPath("\\".get_class($listing));
        $renewOnUpdate = $this->getContainer()->getParameter('ccetc_directory.renew_listing_on_update');
        $contactEmail = $this->getContainer()->getParameter('ccetc_directory.contact_email');

        if(count($listingTypeHelper->getAll()) > 1) {
            $template = 'CCETCDirectoryBundle:Directory:_'.$listingType->getKey().'_pending_expiration_email.html.twig';
        }
        if(!isset($template) || !$this->getContainer()->get('templating')->exists($template) ) {
            $template = 'CCETCDirectoryBundle:Directory:_pending_expiration_email.html.twig';
        }

        $content = $this->getContainer()->get('templating')->render($template, array(
            'listing' => $listing,
            'directoryTitle' => $directoryTitle,
            'renewOnUpdate' => $renewOnUpdate,
            'timeValue' => $timeValue,
            'timeLabel' => $timeLabel,
            'contactEmail' => $contactEmail,
            'editLink' => $this->getPageLink().$this->getContainer()->get('router')->generate($listingType->getEditRouteName(), array('id' => $listing->getId()))
        ));
        
        $message = \Swift_Message::newInstance()
                ->setSubject("Your ".$directoryTitle." Listing is expiring soon")
                ->setFrom('noreply@ccetompkins.org')
                ->setTo($listing->getPrimaryEmail())
                ->setContentType('text/html')
                ->setBody($content)
        ;
        $this->getContainer()->get('mailer')->send($message);
    }
    
    protected function getPageLink()
    {
        $siteUrl = $this->getContainer()->getParameter('ccetc_directory.site_url');
        return $siteUrl;
    }   
}