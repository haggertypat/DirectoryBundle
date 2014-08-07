<?php

namespace CCETC\DirectoryBundle\Helper;

class ListingType
{
 
    protected $container;
    
    protected $entityClassPath;
    protected $translationKey;
    protected $adminService;
    protected $useMaps;
    protected $useProfiles;
    protected $listingsRoutePattern;
    protected $listingsH1Heading;
    protected $listingsMetaDescription;
    protected $listingsMetaTitle;

    public function __construct($container, $configValues)
    {
        $this->container = $container;
    	$this->adminService = $configValues['admin_service'];
    	$this->entityClassPath = $configValues['entity_class_path'];
    	$this->translationKey = $configValues['translation_key'];
        $this->useMaps = $configValues['use_maps'];
        $this->useProfiles = $configValues['use_profiles'];
        if(isset($configValues['listings_meta_description'])) $this->listingsMetaDescription = $configValues['listings_meta_description'];

        if(isset($configValues['listings_meta_title'])) $this->listingsMetaTitle = $configValues['listings_meta_title'];
        else $this->listingsMetaTitle = $this->container->get('translator')->trans(ucfirst($this->translationKey) . 's');

        if(isset($configValues['listings_route_pattern']) && $configValues['listings_route_pattern'] != "") $this->listingsRoutePattern = $configValues['listings_route_pattern'];
        else $this->listingsRoutePattern = '/'.$this->getTranslationKey() . 's';

        if(isset($configValues['listings_h1_heading']) && $configValues['listings_h1_heading'] != "") $this->listingsH1Heading = $configValues['listings_h1_heading'];
        else $this->listingsH1Heading = $this->container->get('translator')->trans(ucfirst($this->translationKey) . 's');
    }

    public function getEntityClassPath()
    {
    	return $this->entityClassPath;
    }

    public function setEntityClassPath($entityClassPath)
    {
    	$this->entityClassPath = $entityClassPath;
    }

    public function getTranslationKey()
    {
    	return $this->translationKey;
    }

    public function setTranslationKey($translationKey)
    {
    	$this->translationKey = $translationKey;
    }

    public function getAdminService()
    {
    	return $this->adminService;
    }

    public function setAdminService($adminService)
    {
    	$this->adminService = $adminService;
    }

    public function getClassName()
    {
    	return substr($this->getEntityClassPath(), strrpos($this->getEntityClassPath(), "\\") + 1);
    }

    public function getAdminClass()
    {
		return $this->container->get($this->getAdminService());
    }

    public function getRepository()
    {
        $bundleName = $this->container->getParameter('ccetc_directory.bundle_name');
    	return $this->container->get('doctrine')->getRepository($bundleName.':'.$this->getClassName());
    }

    public function getKey()
    {
    	return $this->getTranslationKey();
    }

    public function getUseMaps()
    {
        return $this->useMaps;
    }

    public function getUseProfiles()
    {
        return $this->useProfiles;
    }

    public function getListingsRouteName()
    {
    	return $this->getTranslationKey() . 's';    	
    }
    public function getListingsRoutePattern()
    {
    	return $this->listingsRoutePattern;
    }
    public function getSignupRouteName()
    {
    	return $this->getTranslationKey() . 'Signup';
    }
    public function getSignupRoutePattern()
    {
    	return $this->getListingsRoutePattern() . '/signup';
    }
    public function getProfileRouteName()
    {
    	return $this->getTranslationKey() . 'Profile';    	
    }
    public function getProfileRoutePattern()
    {
    	return $this->getListingsRoutePattern() . '/{id}';
    }
    public function getGenerateLocationsRouteName()
    {
    	return $this->getTranslationKey() . 'GenerateLocations';    	
    }
    public function getGenerateLocationsRoutePattern()
    {
    	return $this->getListingsRoutePattern() . 'GenerateLocations';
    }
    public function getEditRouteName()
    {
        return $this->getTranslationKey() . 'Edit';        
    }
    public function getEditRoutePattern()
    {
        return $this->getListingsRoutePattern() . '/{id}/edit';
    }
    public function getListingsH1Heading()
    {
        return $this->listingsH1Heading;
    }
    public function getListingsMetaDescription()
    {
        return $this->listingsMetaDescription;
    }
    public function getListingsMetaTitle()
    {
        return $this->listingsMetaTitle;
    }

}