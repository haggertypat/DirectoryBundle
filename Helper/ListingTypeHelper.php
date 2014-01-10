<?php

namespace CCETC\DirectoryBundle\Helper;

class ListingTypeHelper
{
    protected $container;
    protected $listingTypes;

    public function __construct($container)
    {
        $this->container = $container;

        $this->listingTypes = array();

        foreach($this->container->getParameter('ccetc_directory.listing_type_config') as $singleListingTypeConfig)
        {
        	$listingType = new ListingType($container,
                $singleListingTypeConfig['entity_class_path'],
                $singleListingTypeConfig['translation_key'],
                $singleListingTypeConfig['admin_service']
            );

            $this->listingTypes[] = $listingType;
        }
    }

    public function getListingTypes()
    {
    	return $this->listingTypes;
    }

    

}