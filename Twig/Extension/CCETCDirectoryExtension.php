<?php
namespace CCETC\DirectoryBundle\Twig\Extension;

use Symfony\Component\HttpKernel\KernelInterface;

class CCETCDirectoryExtension extends \Twig_Extension
{
    protected $container;
    
    public function __construct($container)
    {
        $this->container = $container;
        $this->listingTypeHelper = $this->container->get('ccetc.directory.helper.listingtypehelper');
    }

    public function getGlobals() {
        return array(
            'directoryTitle' => $this->container->getParameter('ccetc_directory.title'),
            'directoryLogo' => $this->container->getParameter('ccetc_directory.logo'),
            'directoryMenuBuilder' => $this->container->getParameter('ccetc_directory.menu_builder'),
            'layoutTemplate' => $this->container->getParameter('ccetc_directory.layout_template'),
            'directoryContactEmail' => $this->container->getParameter('ccetc_directory.contact_email'),            
            'directoryCopyright' => $this->container->getParameter('ccetc_directory.copyright'),
            'directoryOgDescription' => $this->container->getParameter('ccetc_directory.og_description'),            
            'directoryOgURL' => $this->container->getParameter('ccetc_directory.og_url'),            
            'googleMapsKey' => $this->container->getParameter('ccetc_directory.google_maps_key'),            
            'googleAnalyticsAccount' => $this->container->getParameter('ccetc_directory.google_analytics_account'), 
            'singleListingType' => $this->listingTypeHelper->getSingleListingType(),
            'registrationSetting' => $this->container->getParameter('ccetc_directory.registration_setting'),
            'allListingTypes' => $this->listingTypeHelper->getAll()
        );
    }
    
    public function getFunctions()
    {
        return array(
            'getListingTypeForObject' => new \Twig_Function_Method($this, 'getListingTypeForObject'),
            'getListingTypeByKey' => new \Twig_Function_Method($this, 'getListingTypeByKey')
        );
    }

    public function getListingTypeForObject($object)
    {
        return $this->listingTypeHelper->findOneByEntityClassPath(get_class($object));
    }

    public function getListingTypeByKey($key)
    {
        return $this->listingTypeHelper->findOneByKey($key);        
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'CCETCDirectoryBundle';
    }
}