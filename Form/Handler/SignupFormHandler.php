<?php

namespace CCETC\DirectoryBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;

class SignupFormHandler
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

        $listing->setApproved(false);
        
        if($listing->getPhotoFile() && $this->form->get('photoFile')->getData()) {
            $listingAdmin->saveFile($listing);
        }

        $listingAdmin->create($listing);

        $this->sendSignupNotificationEmail($listing, $this->container->getParameter('ccetc_directory.admin_email'), $this->getPageLink().$listingAdmin->generateObjectUrl('edit', $listing));
    }
    
    protected function sendSignupNotificationEmail($listing, $to, $link)
    {
        $directoryTitle = $this->container->getParameter('ccetc_directory.title');
        
        $content = $this->container->get('templating')->render('CCETCDirectoryBundle:Directory:_new_listing_admin_email.html.twig', array(
            'listing' => $listing,
            'link' => $link,
            'directoryTitle' => $directoryTitle
        ));
        
        $message = \Swift_Message::newInstance()
                ->setSubject($directoryTitle.' - Sign Up')
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
