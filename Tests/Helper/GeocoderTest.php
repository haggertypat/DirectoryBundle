<?php

namespace CCETC\DirectoryBundle\Tests\Helper;

use CCETC\DirectoryBundle\Helper\Geocoder;

class GeocoderTest extends \PHPUnit_Framework_TestCase
{
   public function testDistanceBetween()
    {
        $geocoder = new Geocoder('dontNeedAValidKeyForThisTest');
        $result = $geocoder->distanceBetween(42.9909060, -76.567978, 42.4355110, -76.525700);

        $this->assertEquals(61.8536267599, $result);
    }

    public function testGeocodeAddress()
    {
    	// google maps key from LBMI
    	// 
    	// what is the proper way to test an api integration like this?
    	// which key should we use?
    	// should we test this for each app with their key? No, we'll test their controllers, which will test this
    	$geocoder = new Geocoder('AIzaSyChMbi3EKId5hm4KhE5p4h1Gm_L6TGbECY');

    	$results = array();
    	$results[] = $geocoder->geocodeAddress('Ithaca'); 
    	$results[] = $geocoder->geocodeAddress('Ithaca NY'); 
    	$results[] = $geocoder->geocodeAddress('Ithaca New York'); 
    	$results[] = $geocoder->geocodeAddress('615 Willow Ave Ithaca NY'); 
    	$results[] = $geocoder->geocodeAddress('14850');

    	foreach($results as $result)
    	{
    		$this->assertNotEmpty($result['lat']);
    		$this->assertNotEmpty($result['lng']);
    	} 
    }
}