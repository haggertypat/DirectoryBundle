<?php

namespace CCETC\DirectoryBundle\Helper;

class ListingTypeHelper
{
    protected $listingTypes;

    public function __construct($container)
    {
        $this->listingTypes = array();

        foreach($container->getParameter('ccetc_directory.listing_type_config') as $singleListingTypeConfig)
        {
        	$listingType = new ListingType(
        		$container,
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

    /*
     * Get the first listing type.  Used in cases where we assume there is only one.
     */
    public function getSingleListingType()
    {
    	return $this->getListingTypes()[0];
    }

    

}