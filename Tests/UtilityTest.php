<?php

namespace CCETC\DirectoryBundle\Tests;

use CCETC\DirectoryBundle\Tests\BaseWebTestCase;

class UtilityTest extends BaseWebTestCase
{
    public function test404()
    {
    	$client = static::createClient();
    	$crawler = $client->request('GET', '/non-existent-page');

		$this->assertTrue($client->getResponse()->isNotFound());
    }
}