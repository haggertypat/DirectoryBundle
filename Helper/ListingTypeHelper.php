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
        	$listingType = new ListingType($container, $singleListingTypeConfig);

            $this->listingTypes[] = $listingType;
        }
    }

    public function getAll()
    {
    	return $this->listingTypes;
    }

    /*
     * Get the first listing type.  Used in cases where we assume there is only one.
     */
    public function getSingleListingType()
    {
    	return $this->getAll()[0];
    }

    public function findOneByKey($key)
    {
    	foreach($this->getAll() as $type)
    	{
    		if($key == $type->getKey()) return $type;
    	}
    }

    public function findOneByEntityClassPath($entityClassPath)
    {
    	foreach($this->getAll() as $type)
    	{
    		if($entityClassPath == $type->getEntityClassPath()) return $type;
    	}    	
    }
    
}