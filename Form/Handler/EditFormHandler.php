<?php

namespace CCETC\DirectoryBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;

class EditFormHandler
{
    protected $request;
    protected $form;
    protected $container;
    
    public function __construct($form, Request $request, $container)
    {
        $this->form = $form;
        $this->request = $request;
        $this->container = $container;
    }

    public function process()
    {
        if('POST' === $this->request->getMethod()) {
            $this->form->bindRequest($this->request);

            if($this->form->isValid()) {
                $this->onSuccess();
                return true;
            } else {
                $this->form->addError(new FormError('Please correct the errors below and re-submit'));
            }

        }
        return false;
    }

    protected function onSuccess()
    {
        $listing = $this->form->getData();
        $listingTypeHelper = $this->container->get('ccetc.directory.helper.listingtypehelper');
        $listingType = $listingTypeHelper->findOneByEntityClassPath("\\".get_class($listing));
        $listingAdmin = $listingType->getAdminClass();

        // don't make "new" listings "edited", they should remain "new" until approved
        if($listing->getStatus() == "active") {
            $listing->setStatus('edited');
        }
        // user renewal: on user edit of expired listing, set to “upForRenewal” instead of “edited”
        if($listing->getStatus() == "expired") {
            $listing->setStatus('upForRenewal');            
        }

        $uow = $this->container->get('doctrine')->getEntityManager()->getUnitOfWork();
        $original = $uow->getOriginalEntityData($listing);
        $originalStatus = $original['status'];
        $newStatus = $listing->getStatus();

        $listingAdmin->update($listing);
        $em = $this->container->get('doctrine')->getEntityManager();
        $em->flush();

        // only send the notification the first time the status changes
        if($originalStatus != $newStatus) {
            $this->sendEditNotificationEmail($listing, $this->container->getParameter('ccetc_directory.admin_email'), $this->getPageLink().$listingAdmin->generateObjectUrl('edit', $listing));

        }
    }
    
    protected function sendEditNotificationEmail($listing, $to, $link)
    {
        $directoryTitle = $this->container->getParameter('ccetc_directory.title');
        
        $content = $this->container->get('templating')->render('CCETCDirectoryBundle:Directory:_edited_listing_admin_email.html.twig', array(
            'listing' => $listing,
            'link' => $link,
            'directoryTitle' => $directoryTitle
        ));
        
        $message = \Swift_Message::newInstance()
                ->setSubject($directoryTitle.' - Listing edited')
                ->setFrom('noreply@ccetompkins.org')
                ->setTo($to)
                ->setContentType('text/html')
                ->setBody($content)
        ;
        $this->container->get('mailer')->send($message);
    }
    
    protected function getPageLink()
    {
        $httpHost = $this->container->get('request')->getHttpHost();
        return 'http://' . $httpHost;
    }   
    
}
