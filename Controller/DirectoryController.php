<?php

namespace CCETC\DirectoryBundle\Controller;

use Symfony\Component\Form\FormError;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
              $template = $this->container->getParameter('ccetc_directory.profile_template');
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
        $request = $this->getRequest();
        $session = $this->getRequest()->getSession();
        $listingAdmin = $this->get('ccetc.directory.admin.listing');
        $listing = new \CCETC\DirectoryBundle\Entity\Listing();
        $listing->setApproved(false);
        $formError = false;
        
        $countyChoices = array(
            'Albany' => 'Albany', 'Allegany' => 'Allegany', 'Bronx' => 'Bronx', 'Broome' => 'Broome', 'Cattaraugus' => 'Cattaraugus', 'Cayuga' => 'Cayuga', 'Chautauqua' => 'Chautauqua', 'Chemung' => 'Chemung', 'Chenango' => 'Chenango', 'Clinton' => 'Clinton', 'Columbia' => 'Columbia', 'Cortland' => 'Cortland', 'Delaware' => 'Delaware', 'Dutchess' => 'Dutchess', 'Erie' => 'Erie', 'Essex' => 'Essex', 'Franklin' => 'Franklin', 'Fulton' => 'Fulton', 'Genesee' => 'Genesee', 'Greene' => 'Greene', 'Hamilton' => 'Hamilton', 'Herkimer' => 'Herkimer', 'Jefferson' => 'Jefferson', 'Kings' => 'Kings', 'Lewis' => 'Lewis', 'Livingston' => 'Livingston', 'Madison' => 'Madison', 'Monroe' => 'Monroe', 'Montgomery' => 'Montgomery', 'Nassau' => 'Nassau', 'New York' => 'New York', 'Niagara' => 'Niagara', 'Oneida' => 'Oneida', 'Onondaga' => 'Onondaga', 'Ontario' => 'Ontario', 'Orange' => 'Orange', 'Orleans' => 'Orleans', 'Oswego' => 'Oswego', 'Otsego' => 'Otsego', 'Putnam' => 'Putnam', 'Queens' => 'Queens', 'Rensselaer' => 'Rensselaer', 'Richmond' => 'Richmond', 'Rockland' => 'Rockland', 'Saint Lawrence' => 'Saint Lawrence', 'Saratoga' => 'Saratoga', 'Schenectady' => 'Schenectady', 'Schoharie' => 'Schoharie', 'Schuyler' => 'Schuyler', 'Seneca' => 'Seneca', 'Steuben' => 'Steuben', 'Suffolk' => 'Suffolk', 'Sullivan' => 'Sullivan', 'Tioga' => 'Tioga', 'Tompkins' => 'Tompkins', 'Ulster' => 'Ulster', 'Warren' => 'Warren', 'Washington' => 'Washington', 'Wayne' => 'Wayne', 'Westchester' => 'Westchester', 'Wyoming' => 'Wyoming', 'Yates' => 'Yates'
        );
        
        $form = $this->createFormBuilder($listing)
            ->add('name', 'text', array('label' => 'Listing Name'))
            ->add('address', 'text')
            ->add('city', 'text')
            ->add('state', 'choice', array('choices' => array('NY' => 'New York')))
            ->add('zip', 'text')
            ->add('county', 'choice', array('required' => true, 'choices' => $countyChoices))
            ->add('website', 'text', array('required' => false))
            ->add('contactName', 'text', array('label' => 'Contact Name'))
            ->add('primaryEmail', 'text', array('label' => 'E-mail', 'required' => false))
            ->add('primaryPhone', 'text', array('label' => 'Phone', 'required' => false))
            ->add('description', 'textarea', array('label' => 'listing Description', 'attr' => array('rows' => '5'), 'required' => false))
            ->add('products', null, array('label' => 'Products', 'expanded' => true, 'required' => false))
            ->add('attributes', null, array('label' => 'Attributes', 'expanded' => true, 'required' => false))
        ;
        
        $form = $form->getForm();
        
        if ($request->isMethod('POST')) {
            $form->bind($request);
            
            if ($form->isValid() && !$formError) {
                if($form->get('photoFile')->getData()) {
                    $listingAdmin->saveFile($listing);
                }
                
                $listingAdmin->create($listing);
                
                $this->sendSignupNotificationEmail($listing, $this->container->getParameter('ccetc_directory.admin_email'), $this->getPageLink().$listingAdmin->generateObjectUrl('edit', $listing));
                
                $session->setFlash('template-flash', 'CCETCDirectoryBundle:Directory:_signup_thanks.html.twig');
                return $this->redirect($this->generateUrl('home'));
            } else {
                $formError = true;
            }
        }
        
        if($formError) {
            $form->addError(new FormError('Please correct the errors below and re-submit'));
        }
        
        $templateParameters = array(
            'form' => $form->createView(),
        );
        
        return $this->render('CCETCDirectoryBundle:Directory:signup.html.twig', $templateParameters);                
    }

    protected function sendSignupNotificationEmail($listing, $to, $link)
    {
        $message = \Swift_Message::newInstance()
                ->setSubject('Local Building Materials Directory - Sign Up')
                ->setFrom('noreply@ccetompkins.org')
                ->setTo($to)
                ->setContentType('text/html')
                ->setBody('<html>
                       <a href="mailto:'.$listing->getPrimaryemail().'">'.$listing->getContactName().'</a> from '.$listing->getlistingName().' signed up for the Local Building Materials Directory.<br/><br/>
                       Approve their listing here: <a href="'.$link.'">' . $link . '</a></html>')
        ;
        $this->get('mailer')->send($message);
    }
    
    protected function getPageLink()
    {
        $httpHost = $this->container->get('request')->getHttpHost();
        return 'http://' . $httpHost;
    }    
}
