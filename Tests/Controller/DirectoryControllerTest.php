<?php

namespace CCETC\DirectoryBundle\Tests\Controller;

use CCETC\DirectoryBundle\Tests\BaseWebTestCase;

class DirectoryControllerTest extends BaseWebTestCase
{
    public function testListingsLoads()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/listings');

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function testListingViewBtn()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/listings');

        $container = $client->getContainer();

        $useProfiles = $container->getParameter('ccetc_directory.use_profiles');

        if($useProfiles) {
            $this->assertGreaterThan(0, $crawler->filter('a.view-btn')->count(), 'view button not visible but should be');
        } else {
            $this->assertEquals(0, $crawler->filter('a.view-btn')->count(), 'view button visible but should not be');          
        }
    }

    public function testFilters()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/listings');

        // TODO: I hate having to select this button by the text - what if I offer i18n support or a client wants to change the text for their installation?
		$buttonCrawlerNode = $crawler->selectButton('Go');
        $form = $buttonCrawlerNode->form();

        $form['filter']['products']['value']->select('1');
        $form['filter']['attributes']['value'][0]->tick();


		$crawler = $client->submit($form);

        echo $crawler->filter('.listing-block-container')->count();

		$this->assertTrue($client->getResponse()->isSuccessful(), 'filter form cannot be submitted');
    }

    public function testProfileLoads()
    {
        $client = static::createClient();

        $listing = $this->findOneApprovedListing();

        $crawler = $client->request('GET', '/listings/'.$listing->getId());        

        $this->assertTrue($client->getResponse()->isSuccessful());        
    }

    /*
       Apps can choose whether or not each listing gets its own profile page.  For apps with the feature disabled, we just show the listings page with one listing.
    */
    public function testProfileTypes()
    {
        $client = static::createClient();

        $container = $client->getContainer();

        $useProfiles = $container->getParameter('ccetc_directory.use_profiles');

        $listing = $this->findOneApprovedListing();

        $crawler = $client->request('GET', '/listings/'.$listing->getId());        

        if($useProfiles) {
            $this->assertEquals(
                1,
                $crawler->filter('#profile-heading')->count(),
                'profile page not used for app with "useProfiles" enabled'
            );
        } else {
            $this->assertEquals(
                1,
                $crawler->filter('#single-listing-message')->count(),
                'single listing page not used for app with "useProfiles" disabled'
            );
        }
    }

    public function testUnapprovedProfileHiddenFromRegularUser()
    {
        $client = static::createClient();

        $container = $client->getContainer();

        $listing = $this->findOneUnapprovedListing();

        $crawler = $client->request('GET', '/listings/'.$listing->getId());        

        $this->assertEquals(500, $client->getResponse()->getStatusCode());
    }

    public function testUnapprovedProfileVisibleForAdmin()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin',
        ));

        $container = $client->getContainer();

        $listing = $this->findOneUnapprovedListing();

        $crawler = $client->request('GET', '/listings/'.$listing->getId());        

        $this->assertTrue($client->getResponse()->isSuccessful());        
    }

}