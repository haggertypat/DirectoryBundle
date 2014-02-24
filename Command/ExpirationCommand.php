<?php
namespace CCETC\DirectoryBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExpirationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ccetc:directory:update-expired-listings')
            ->setDescription('mark outdated Listings as "expired"')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $listingTypeHelper = $this->getContainer()->get('ccetc.directory.helper.listingtypehelper');

        foreach($listingTypeHelper->getAll() as $listingType) {
            $listingsUpdatedCount= 0;

            $listingAdmin = $listingType->getAdminClass();
            $listings = $listingType->getRepository()->findAll();

            foreach($listings as $listing)
            {
                if($listing->shouldBeExpired() && $listing->getStatus() != "expired") {
                    $listing->setStatus('expired');
                    $listingAdmin->update($listing);
                    $this->sendNotification($listing);
                    $listingsUpdatedCount++;
                }
            }

            $output->writeln($listingsUpdatedCount.' '.ucfirst($listingType->getKey()).'s marked "expired"');            
        }
    }

    protected function sendNotification($listing)
    {
        $directoryTitle = $this->getContainer()->getParameter('ccetc_directory.title');
        $listingTypeHelper = $this->getContainer()->get('ccetc.directory.helper.listingtypehelper');
        $listingType = $listingTypeHelper->findOneByEntityClassPath("\\".get_class($listing));
        $renewOnUpdate = $this->getContainer()->getParameter('ccetc_directory.renew_listing_on_update');
        $contactEmail = $this->getContainer()->getParameter('ccetc_directory.contact_email');

        if(count($listingTypeHelper->getAll()) > 1) {
            $template = 'CCETCDirectoryBundle:Directory:_'.$listingType->getKey().'_expiration_email.html.twig';
        }
        if(!isset($template) || !$this->getContainer()->get('templating')->exists($template) ) {
            $template = 'CCETCDirectoryBundle:Directory:_expiration_email.html.twig';
        }

        $content = $this->getContainer()->get('templating')->render($template, array(
            'listing' => $listing,
            'directoryTitle' => $directoryTitle,
            'renewOnUpdate' => $renewOnUpdate,
            'contactEmail' => $contactEmail,
            'editLink' => $this->getPageLink().$this->getContainer()->get('router')->generate($listingType->getEditRouteName(), array('id' => $listing->getId()))
        ));
        
        $message = \Swift_Message::newInstance()
                ->setSubject("Your ".$directoryTitle." Listing has expired")
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