<?php

namespace CCETC\DirectoryBundle\Helper;

class ListingType
{
 
    protected $container;
    
    protected $entityClassPath;
    protected $translationKey;
    protected $adminService;
    
    public function __construct($container, $entityClassPath, $translationKey, $adminService)
    {
        $this->container = $container;
    	$this->translationKey = $translationKey;
    	$this->entityClassPath = $entityClassPath;
    	$this->adminService = $adminService;
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

    public function getListingsRouteName()
    {
    	return $this->getTranslationKey() . 's';    	
    }
    public function getListingsRoutePattern()
    {
    	return '/'.$this->getTranslationKey() . 's';
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

}