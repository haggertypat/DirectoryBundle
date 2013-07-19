<?php

namespace CCETC\DirectoryBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseWebTestCase extends WebTestCase
{
    protected function findOneApprovedListing()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $bundleName = $container->getParameter('ccetc_directory.bundle_name');
        $listingRepository = $container->get('doctrine')->getRepository($bundleName.':Listing');

        return $listingRepository->findOneByApproved(true);
    }
    protected function findOneUnapprovedListing()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $bundleName = $container->getParameter('ccetc_directory.bundle_name');
        $listingRepository = $container->get('doctrine')->getRepository($bundleName.':Listing');

        return $listingRepository->findOneByApproved(false);
    }    
}