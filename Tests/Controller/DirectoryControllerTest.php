<?php

namespace CCETC\DirectoryBundle\Tests\Controller;

use CCETC\DirectoryBundle\Tests\BaseWebTestCase;

class DirectoryControllerTest extends BaseWebTestCase
{
    // just test that the form can be submitted, and that it takes you to a listings search
    public function testFindAListing()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $buttonCrawlerNode = $crawler->selectButton('find-a-listing-submit');

        $form = $buttonCrawlerNode->form();

        $form['filter']['products']['value']->select('1');
        $form['filter[location][type]'] = 25;
        $form['filter[location][value]'] = '14850';

        $crawler = $client->submit($form);


        $this->assertTrue($client->getResponse()->isSuccessful(), 'filter form cannot be submitted');

        $this->assertEquals(0, $crawler->filter('#browse-all-message')->count(), 'browse all message is visible');  

        $this->assertTrue(
            $crawler->filter('#search-results-message')->count() == 1 || $crawler->filter('#no-search-results-message')->count() == 1,
            'search results message is not visible'
        );          
    }

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

    public function testFilterFormCanBeSubmitted()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/listings');

        $this->assertEquals(1, $crawler->filter('#browse-all-message')->count(), 'browse all message is not visible');          

		$buttonCrawlerNode = $crawler->selectButton('filter-form-submit');
        $form = $buttonCrawlerNode->form();

        $form['filter']['products']['value']->select('1');
        $form['filter']['attributes']['value'][0]->tick();
        $form['filter[location][type]'] = 25;
        $form['filter[location][value]'] = '14850';

		$crawler = $client->submit($form);

		$this->assertTrue($client->getResponse()->isSuccessful(), 'filter form cannot be submitted');

        $this->assertEquals(0, $crawler->filter('#browse-all-message')->count(), 'browse all message is visible');  

        $this->assertTrue(
            $crawler->filter('#search-results-message')->count() == 1 || $crawler->filter('#no-search-results-message')->count() == 1,
            'search results message is not visible'
        );          

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

    public function testSignupLoads()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/signup');

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

}