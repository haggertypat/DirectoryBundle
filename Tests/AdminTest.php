<?php

namespace CCETC\DirectoryBundle\Tests;

use CCETC\DirectoryBundle\Tests\BaseWebTestCase;

class AdminTest extends BaseWebTestCase
{
    public function testAccessDenied()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/dashboard');

		$this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testAdminPagesLoad()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
        $container = $client->getContainer();        

        $listingAdmin = $container->get('ccetc.directory.admin.listing');
        $listing = $this->findOneApprovedListing();


        $routes = array(
            '/admin/dashboard',
            $listingAdmin->generateUrl('list'),
            // for some reason, I can't get generateObjectUrl to work in the test env
            $listingAdmin->generateUrl('show', array('id' => $listing->getId())),
            $listingAdmin->generateUrl('edit', array('id' => $listing->getId())),
            $listingAdmin->generateUrl('delete', array('id' => $listing->getId())), // just load the  confirmation page
        );

        foreach($routes as $route)
        {
            $crawler = $client->request('GET', $route);
            $this->assertTrue($client->getResponse()->isSuccessful(), 'could not load '.$route);
        }

    }

    public function testAdminMenuDoesNotExistForRegularUsers()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $crawler = $client->request('GET', '/listings');

        $this->assertEquals(
            0,
            $crawler->filter('#frontend-admin-menu')->count(),
            'admin menu exists for non admin'
        );
    }

    public function testAdminMenuExistsForAdmins()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
        $crawler = $client->request('GET', '/listings');

        $this->assertEquals(
            1,
            $crawler->filter('#frontend-admin-menu')->count(),
            'admin menu does not exist for admin'                
        );            
    }

    public function testEditBtnsOnListingsDoNotExistForRegularUsers()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $crawler = $client->request('GET', '/listings');

        $this->assertEquals(
            0,
            $crawler->filter('a.edit-btn')->count(),
            'listings edit btns exist for non admin'
        );
    }

    public function testEditBtnsOnListingsExistForAdmins()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
        $crawler = $client->request('GET', '/listings');

        $this->assertGreaterThan(
            0,
            $crawler->filter('a.edit-btn')->count(),
            'listings edit btns do not exist for admin'                
        );            
    }

    public function testEditBtnOnProfileDoesNotExistForRegularUsers()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $useProfiles = $container->getParameter('ccetc_directory.use_profiles');
        if(!$useProfiles) return;

        $listing = $this->findOneApprovedListing();
        $crawler = $client->request('GET', '/listings/'.$listing->getId());

        $this->assertEquals(
            0,
            $crawler->filter('a.edit-btn')->count(),
            'profile edit btn exists for non admin'
        );
    }

    public function testEditBtnOnProfileExistForAdmins()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));
        $container = $client->getContainer();
        $useProfiles = $container->getParameter('ccetc_directory.use_profiles');
        if(!$useProfiles) return;

        $listing = $this->findOneApprovedListing();
        $crawler = $client->request('GET', '/listings/'.$listing->getId());

        $this->assertEquals(
            1,
            $crawler->filter('a.edit-btn')->count(),
            'profile edit btn does not exist for admin'                
        );            
    }
}