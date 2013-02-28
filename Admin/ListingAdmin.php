<?php
namespace CCETC\DirectoryBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Doctrine\ORM\Query\Expr\Join;

class ListingAdmin extends Admin
{
    public function getFilterParameters()
    {
        $this->datagridValues = array_merge(array(
                'spam' => array(
                    'value' => 2,
                )
            ),
            $this->datagridValues
        );

        return parent::getFilterParameters();
    }    
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        if($this->subject->getId()) {
            $photoLabel = "Change Photo";
        } else {
            $photoLabel = "Photo";
        }        
        $formMapper
            ->with('General')
                ->add('name')
                ->add('contactName')
                ->add('website')
                ->add('description')
                ->add('photoFile', 'file', array('required' => false, 'label' => $photoLabel))
                ->add('spam', null, array('required' => false))
                ->add('approved', null, array('required' => false))
            ->end()
            ->with('Primary Address')
                ->add('address')
                ->add('city')
                ->add('state')
                ->add('zip')
                ->add('county')
                ->add('addressLabel')
            ->end()
            ->with('Secondary Address')
                ->add('address2', null, array('label' => 'Address'))
                ->add('city2', null, array('label' => 'City'))
                ->add('state2', null, array('label' => 'State'))
                ->add('zip2', null, array('label' => 'Zip'))
                ->add('county2', null, array('label' => 'County'))
                ->add('addressLabel2', null, array('label' => 'Address Label'))
            ->end()
            ->with('Contact')
                ->add('primaryEmail')
                ->add('primaryEmailType')
                ->add('secondaryEmail')
                ->add('secondaryEmailType')
                ->add('primaryPhone')
                ->add('primaryPhoneType')
                ->add('secondaryPhone')
                ->add('secondaryPhoneType')
            ->end()
            ->with('Products')
                ->add('products', null, array('expanded' => true, 'required' => false))
                ->add('attributes', null, array('expanded' => true, 'required' => false))
            ->end()
            ->setHelps(array(
                'addressLabel' => 'Office, Mailing, etc.',
                'addressLabel2' => 'Office, Mailing, etc.',
            ))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('spam')
            ->add('approved')
            ->add('products')
            ->add('attributes')
        ;
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();

        $actions['approve'] = array(
            'label' => 'Approve Selected',
            'ask_confirmation' => false
        );

        $actions['unapprove'] = array(
            'label' => 'Un-Approve Selected',
            'ask_confirmation' => false
        );
        
        $actions['spam'] = array(
            'label' => 'Mark Selected as Spam',
            'ask_confirmation' => false
        );

        $actions['unspam'] = array(
            'label' => 'Mark Selected as Not Spam',
            'ask_confirmation' => false
        );
        
        return $actions;
    }    
    
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('address', null, array('template' => 'CCETCDirectoryBundle:Admin:_listing_list_address.html.twig'))
            ->add('contactName', null, array('label' => 'Contact', 'template' => 'CCETCDirectoryBundle:Admin:_listing_list_contact.html.twig'))
            ->add('spam', null, array('editable' => true))
            ->add('approved', null, array('editable' => true))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'view' => array(),
                    'edit' => array(),
                )
            ))        
        ;
    }
    
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('General')
                ->add('name', null, array('template' => 'CCETCDirectoryBundle:Admin:_listing_show_name.html.twig'))
                ->add('contactName')
                ->add('website')
                ->add('description')
                ->add('photoFilename', null, array('template' => 'CCETCDirectoryBundle:Admin:_listing_show_photo.html.twig'))
                ->add('spam')
                ->add('approved')
                ->add('datetimeCreated')
                ->add('datetimeLastUpdated')
            ->end()
                ->with('Primary Address')
                ->add('address')
                ->add('city')
                ->add('state')
                ->add('zip')
                ->add('county')
                ->add('addressLabel')
            ->end()
            ->with('Secondary Address')
                ->add('address2', null, array('label' => 'Address'))
                ->add('city2', null, array('label' => 'City'))
                ->add('state2', null, array('label' => 'State'))
                ->add('zip2', null, array('label' => 'Zip'))
                ->add('county2', null, array('label' => 'County'))
                ->add('addressLabel2', null, array('label' => 'Address Label'))
           ->end()

            ->with('Contact')
                ->add('primaryEmail')
                ->add('primaryEmailType')
                ->add('secondaryEmail')
                ->add('secondaryEmailType')
                ->add('primaryPhone')
                ->add('primaryPhoneType')
                ->add('secondaryPhone')
                ->add('secondaryPhoneType')
            ->end()
            ->with('Products')
                ->add('products')
                ->add('attributes')
            ->end()                
        ;
        
    }
    
    public function prePersist($object)
    {
        $object->setDatetimeCreated(new \DateTime());

        if($object->getPhotoFile()) {
            $this->saveFile($object);
        }
        
        if(!$object->getSpam()) $object->setSpam(false);
        if(!$object->getApproved()) $object->setApproved(true);
        
        $this->geocodeAddress($object);

        parent::prePersist($object);
    }
        
    public function preUpdate($object)
    {
        $object->setDatetimeLastUpdated(new \DateTime());
        
        if($object->getPhotoFile()) {
            $this->saveFile($object);
        }
        
        $this->geocodeAddress($object);
        
        parent::preUpdate($object);        
    }
    
    public function geocodeAddress($object)
    {
        $geocoder = $this->configurationPool->getContainer()->get('ccetc.directory.helper.geocoder');
        
        $stringsToCheck = array(
            $object->getAddress().' '.$object->getCity().', '.$object->getState().' '.$object->getZip(),
            $object->getCity().', '.$object->getState().' '.$object->getZip(),
            $object->getZip()
        );
        
        foreach($stringsToCheck as $string)
        {
            $result = $geocoder->geocodeAddress($string);
            if(isset($result['lat']) && isset($result['lng'])) {
                $object->setLat($result['lat']);
                $object->setLng($result['lng']);
                return;
            }
        }
    }
    
    public function saveFile($object)
    {
        $object->uploadPhoto();
    }  
    
    public function findForDirectory($filters = null, $searchTerms = null)
    {
        $bundleName = $this->configurationPool->getContainer()->getParameter('ccetc_directory.bundle_name');
        $listingRepository = $this->configurationPool->getContainer()->get('doctrine')->getRepository($bundleName.':Listing');
        $geocoder = $this->configurationPool->getContainer()->get('ccetc.directory.helper.geocoder');

        $query = $listingRepository->createQueryBuilder('l');
        
        if(isset($filters['product']) && $filters['product'] != 0) {
            $query    
                ->join("l.products", "p")
                ->andWhere('p.id = :productId')
                ->setParameter('productId', $filters['product']);
        }

        if(isset($filters['attributes']) && $filters['attributes'] != 0) {
            foreach($filters['attributes'] as $attributeId) 
            {
                $query->join("l.attributes", "a".$attributeId, Join::WITH, "a".$attributeId.".id=".$attributeId);
            }
        }

        if(isset($searchTerms) && $searchTerms != "") {
            $query    
                ->andWhere('l.name LIKE :searchTerms')
                ->setParameter('searchTerms', '%' . $searchTerms . '%');
        }
        
        $query->andWhere('l.approved = 1');
        $query->orderBy('l.name', 'ASC');

        $results = $query->getQuery()->getResult();

        if(isset($filters['address']) && trim($filters['address']) != "" && isset($filters['miles'])) {
            $newResults = array();

            $geocodedAddress = $geocoder->geocodeAddress($filters['address']);
            
            foreach($results as $listing)
            {
                if(isset($geocodedAddress['lat']) && isset($geocodedAddress['lng']) && $listing->getLat() && $listing->getLng()
                        && $geocoder->distanceBetween($listing->getLat(), $listing->getLng(), $geocodedAddress['lat'], $geocodedAddress['lng']) <= $filters['miles']) { 
                            $newResults[] = $listing;
                }
            }

            $results = $newResults;
        }
        
        return $results;
    }    
}