<?php
namespace CCETC\DirectoryBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Doctrine\ORM\Query\Expr\Join;

/*
 * This has no corresponding service, it's just meant to me extended by the location admin classes
 */
class BaseLocationAdmin extends Admin
{
    public function postPersist($thisLocation)
    {
        $geocoder = $this->configurationPool->getContainer()->get('ccetc.directory.helper.geocoder');
        $bundleName = $this->configurationPool->getContainer()->getParameter('ccetc_directory.bundle_name');
        $bundlePath = $this->configurationPool->getContainer()->getParameter('ccetc_directory.bundle_path');
        $locationDistanceAdmin = $this->configurationPool->getContainer()->get('ccetc.directory.admin.locationdistance');
        $locationDistanceEntityPath = $bundlePath . '\Entity\LocationDistance';
        
        if($this->getClassnameLabel() == "ListingLocation") {
            $otherLocationRepositoryPath = $bundleName.':UserLocation';
        } else {
            $otherLocationRepositoryPath = $bundleName.':ListingLocation';
        }
        $otherLocationRepository = $this->configurationPool->getContainer()->get('doctrine')->getRepository($otherLocationRepositoryPath);
        
        foreach($otherLocationRepository->findAll() as $otherLocation)
        {
            $locationDistance = new $locationDistanceEntityPath();
            
            if($this->getClassnameLabel() == "ListingLocation") {
                $locationDistance->setListingLocation($thisLocation);
                $locationDistance->setUserLocation($otherLocation);
            } else {
                $locationDistance->setListingLocation($otherLocation);
                $locationDistance->setUserLocation($thisLocation);                
            }
            
            $distance = $geocoder->distanceBetween($thisLocation->getLat(), $thisLocation->getLng(), $otherLocation->getLat(), $otherLocation->getLng());
            
            $locationDistance->setDistance($distance);
            $locationDistanceAdmin->create($locationDistance);
        }        
    }
    
    public function postUpdate($thisLocation)
    {
        $geocoder = $this->configurationPool->getContainer()->get('ccetc.directory.helper.geocoder');
        $bundleName = $this->configurationPool->getContainer()->getParameter('ccetc_directory.bundle_name');
        $bundlePath = $this->configurationPool->getContainer()->getParameter('ccetc_directory.bundle_path');
        $locationDistanceAdmin = $this->configurationPool->getContainer()->get('ccetc.directory.admin.locationdistance');
        
        foreach($thisLocation->getDistances() as $locationDistance)
        {
            if($this->getClassnameLabel() == "ListingLocation") {
                $otherLocation = $locationDistance->getUserLocation();
            } else {
                $otherLocation = $locationDistance->getListingLocation();
            }
            $distance = $geocoder->distanceBetween($thisLocation->getLat(), $thisLocation->getLng(), $otherLocation->getLat(), $otherLocation->getLng());
            $locationDistance->setDistance($distance);
            $locationDistanceAdmin->update($locationDistance);
        }
    }
}