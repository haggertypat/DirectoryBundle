<?php
namespace CCETC\DirectoryBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Doctrine\ORM\Query\Expr\Join;

class ListingLocationAdmin extends Admin
{
    public function postPersist($listingLocation)
    {
        $geocoder = $this->configurationPool->getContainer()->get('ccetc.directory.helper.geocoder');
        $bundleName = $this->configurationPool->getContainer()->getParameter('ccetc_directory.bundle_name');
        $bundlePath = $this->configurationPool->getContainer()->getParameter('ccetc_directory.bundle_path');
        $locationDistanceEntityPath = $bundlePath . '\Entity\LocationDistance';
        $userLocationRepositoryPath = $bundleName.':UserLocation';
        $locationDistanceAdmin = $this->configurationPool->getContainer()->get('ccetc.directory.admin.locationdistance');
        $userLocationRepository = $this->configurationPool->getContainer()->get('doctrine')->getRepository($userLocationRepositoryPath);
        
        foreach($userLocationRepository->findAll() as $userLocation)
        {
            $locationDistance = new $locationDistanceEntityPath();
            $locationDistance->setListingLocation($listingLocation);
            $locationDistance->setUserLocation($userLocation);
            
            $distance = $geocoder->distanceBetween($listingLocation->getLat(), $listingLocation->getLng(), $userLocation->getLat(), $userLocation->getLng());
            
            $locationDistance->setDistance($distance);
            $locationDistanceAdmin->create($locationDistance);
        }        
    }
    
    public function postUpdate($listingLocation)
    {
        $geocoder = $this->configurationPool->getContainer()->get('ccetc.directory.helper.geocoder');
        $bundleName = $this->configurationPool->getContainer()->getParameter('ccetc_directory.bundle_name');
        $bundlePath = $this->configurationPool->getContainer()->getParameter('ccetc_directory.bundle_path');
        $locationDistanceAdmin = $this->configurationPool->getContainer()->get('ccetc.directory.admin.locationdistance');
        
        foreach($listingLocation->getDistances() as $locationDistance)
        {
            $userLocation = $locationDistance->getUserLocation();
            $distance = $geocoder->distanceBetween($listingLocation->getLat(), $listingLocation->getLng(), $userLocation->getLat(), $userLocation->getLng());
            $locationDistance->setDistance($distance);
            $locationDistanceAdmin->update($locationDistance);
        }
    }
}