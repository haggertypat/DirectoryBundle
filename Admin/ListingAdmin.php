<?php
namespace CCETC\DirectoryBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Doctrine\ORM\Query\Expr\Join;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

class ListingAdmin extends Admin
{
    public function getFilterParameters()
    {
        if($this->isAdmin()) {
            $this->datagridValues = array_merge(array(
                    'spam' => array(
                        'value' => 2,
                    )
                ),
                $this->datagridValues
            );            
        } else {
            
            $this->datagridValues = array_merge(array(
                    '_sort_order' => 'ASC', 
                    '_sort_by' => 'name',
                ),
                $this->datagridValues
            );            

            // we have to set the maxPerPage twice, the datagrid value is set on construct,
            // but we don't have the request on construct to know if we should change this
            $this->maxPerPage = 10;            
            $this->datagridValues['_per_page'] = $this->maxPerPage;
            
            // update per page options
            $this->predefinePerPageOptions();
        }
        

        return parent::getFilterParameters();
    }    
    
    public function createQuery($context = 'list') 
    { 
        $queryBuilder = $this->getModelManager()->getEntityManager($this->getClass())->createQueryBuilder();

        $queryBuilder->select('e')->from($this->getClass(), 'e');

        if(!$this->isAdmin()) {
            $queryBuilder->andWhere('e.approved=1');
        }

        $proxyQuery = new ProxyQuery($queryBuilder);
        
        return $proxyQuery;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        if($this->isAdmin()) {
            $datagridMapper
                ->add('name')
                ->add('spam')
                ->add('approved')
            ;
        } else {
            $datagridMapper
                ->add('location', 'ccetc_directory_filter_location')
            ;
        }
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
                ->add('secondaryContactName')
                ->add('website')
                ->add('description')
                ->add('photoFile', 'file', array('required' => false, 'label' => $photoLabel))
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
                ->add('preferredMethodOfContact')
                ->add('primaryEmail')
                ->add('primaryEmailType')
                ->add('secondaryEmail')
                ->add('secondaryEmailType')
                ->add('primaryPhone')
                ->add('primaryPhoneType')
                ->add('secondaryPhone')
                ->add('secondaryPhoneType')
            ->end()
            ->with('Status')
                ->add('spam', null, array('required' => false))
                ->add('approved', null, array('required' => false))
            ->end()
            ->setHelps(array(
                'addressLabel' => 'Example: Office, Mailing, etc.',
                'addressLabel2' => 'Example: Office, Mailing, etc.',
            ))
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
                ->add('secondaryContactName')
                ->add('website')
                ->add('description')
                ->add('photoFilename', null, array('template' => 'CCETCDirectoryBundle:Admin:_listing_show_photo.html.twig'))
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
                ->add('preferredMethodOfContact')
                ->add('primaryEmail')
                ->add('primaryEmailType')
                ->add('secondaryEmail')
                ->add('secondaryEmailType')
                ->add('primaryPhone')
                ->add('primaryPhoneType')
                ->add('secondaryPhone')
                ->add('secondaryPhoneType')
            ->end()
            ->with('Status')
                ->add('spam')
                ->add('approved')
                ->add('datetimeCreated')
                ->add('datetimeLastUpdated')
            ->end()      
        ;
        
    }
    
    public function prePersist($object)
    {
        $object->setDatetimeCreated(new \DateTime());

        if($object->getPhotoFile()) {
            $this->saveFile($object);
        }

        parent::prePersist($object);
    }
    
    public function postPersist($object)
    {
        $this->setLocation($object);        
    }
        
    public function preUpdate($object)
    {
        $object->setDatetimeLastUpdated(new \DateTime());
        
        if($object->getPhotoFile()) {
            $this->saveFile($object);
        }
        
        $this->updateLocation($object);
        
        parent::preUpdate($object);        
    }
    
    public function setLocation($object)
    {
        $geocodeResult = $this->geocodeAddress($object);
        $bundlePath = $this->configurationPool->getContainer()->getParameter('ccetc_directory.bundle_path');
        
        if(isset($geocodeResult['lat']) && isset($geocodeResult['lng'])) {
            $listingLocationAdmin = $this->configurationPool->getContainer()->get('ccetc.directory.admin.listinglocation');
            $listingLocationEntityPath = $bundlePath.'\Entity\ListingLocation';
            $listingLocation = new $listingLocationEntityPath();
            $listingLocation->setListing($object);
            $listingLocation->setLat($geocodeResult['lat']);
            $listingLocation->setLng($geocodeResult['lng']);
            
            $listingLocationAdmin->create($listingLocation);
            
            $object->setLocation($listingLocation);
        }
    }
    
    public function updateLocation($object)
    {
        $geocodeResult = $this->geocodeAddress($object);
        
        if(!$object->getLocation()) {
            $this->setLocation($object);
        } else if(isset($geocodeResult['lat']) && isset($geocodeResult['lng'])
                && ($geocodeResult['lat'] != $object->getLocation()->getLat() || $geocodeResult['lng'] != $object->getLocation()->getLng())) {
            $listingLocationAdmin = $this->configurationPool->getContainer()->get('ccetc.directory.admin.listinglocation');
            $object->getLocation()->setLat($geocodeResult['lat']);
            $object->getLocation()->setLng($geocodeResult['lng']);
            $listingLocationAdmin->update($object->getLocation());
        }
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
                return $result;
            }
        }
    }
    
    public function saveFile($object)
    {
        $object->uploadPhoto();
    }  
    
    public function isAdmin()
    {
        return strstr($this->getRequest()->getUri(), 'admin');
    }
    
    public function getRelationChoices($repository, $field = 'name', $attribute = false) {
        $repository = $this->configurationPool->getContainer()->get('doctrine')->getRepository($repository);
        
        $constraints = array();
        
        if($attribute) {
            $constraints['searchable'] = true;
        }
        
        $entities = $repository->findBy($constraints, array($field => 'ASC'));
        $choices = array();
        foreach($entities as $entity)
        {
            $choices[$entity->getId()] = $entity->__toString();
        }
        
        return $choices;        
    }
    
    public function getDatagridParameters($datagrid, $additions = array())
    {
        $parameters = array (
            'filter' => $datagrid->getValues()
        );
        
        foreach($additions as $key => $value)
        {
          $parameters[$key] = $value;
        }
        
        return $parameters;
    }    
}