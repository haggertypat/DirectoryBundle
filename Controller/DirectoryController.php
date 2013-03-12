<?php

namespace CCETC\DirectoryBundle\Controller;

use Symfony\Component\Form\FormError;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use CCETC\DirectoryBundle\Form\Type\SignupFormType;
use CCETC\DirectoryBundle\Form\Handler\SignupFormHandler;

class DirectoryController extends Controller
{
    public function listingsAction()
    {
        $listingAdmin = $this->get('ccetc.directory.admin.listing');
        
        $listings = $listingAdmin->findForDirectory($this->getRequest()->get('filters'), $this->getRequest()->get('searchTerms'));

        $templateParameters = array(
            'listingAdmin' => $listingAdmin,
            'listings' => $listings,
        );
        
        $templateParameters['filters'] = $this->getRequest()->get('filters');
        $templateParameters['searchTerms'] = $this->getRequest()->get('searchTerms');
        
        return $this->render('CCETCDirectoryBundle:Directory:listings.html.twig', $templateParameters);
    }
    public function profileAction($id)
    {
        $bundleName = $this->container->getParameter('ccetc_directory.bundle_name');
        $listingAdmin = $this->get('ccetc.directory.admin.listing');
        $listingRepository = $this->getDoctrine()->getRepository($bundleName.':Listing');
        $listing = $listingRepository->findOneById($id);

        if($listing->getApproved() || $this->get('security.context')->isGranted('ROLE_ADMIN') ) {
              $template = 'CCETCDirectoryBundle:Directory:profile.html.twig';
        } else {
              $template = 'CCETCDirectoryBundle:Directory:profile_unapproved.html.twig';
        }
        return $this->render($template, array(
            'listingAdmin' => $listingAdmin,
            'listing' => $listing
        ));                                    
        
    }
    public function findAListingAction($filters = null)
    {
        $bundleName = $this->container->getParameter('ccetc_directory.bundle_name');
        $productRepository = $this->getDoctrine()->getRepository($bundleName.':Product');
        $attributeRepository = $this->getDoctrine()->getRepository($bundleName.':Attribute');
        
        $templateParameters = array(
            'filters' => $filters,
            'products' => $productRepository->findAll(),
            'attributes' => $attributeRepository->findAll()
        );
        
        return $this->render('CCETCDirectoryBundle:Directory:_find_a_listing.html.twig', $templateParameters);        
    }
    
    public function signupAction()
    {
        $bundlePath = $this->container->getParameter('ccetc_directory.bundle_path');
        $session = $this->getRequest()->getSession();
        $form = $this->container->get('ccetc.directory.form.signup');
        $formHandler = $this->container->get('ccetc.directory.form.handler.signup');
        
        if ($formHandler->process()) {
            $session->setFlash('template-flash', 'CCETCDirectoryBundle:Directory:_signup_thanks.html.twig');
            return $this->redirect($this->generateUrl('home'));
        }
        
        $templateParameters = array(
            'form' => $form->createView(),
        );
        
        return $this->render('CCETCDirectoryBundle:Directory:signup.html.twig', $templateParameters);                
    }
}
