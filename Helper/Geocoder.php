<?php

namespace CCETC\DirectoryBundle\Helper;

class Geocoder
{
    protected $googleMapsKey;
    
    public function __construct($googleMapsKey)
    {
        $this->googleMapsKey = $googleMapsKey;
    }
    
    public function geocodeAddress($string)
    {
        $adr = urlencode($string);
        $url = "http://maps.googleapis.com/maps/api/geocode/xml?sensor=false&address=".$adr;

        $xml = simplexml_load_file($url);
        
        $status = $xml->status;
        
        if($status == 'OK') {
            
            $numResults = count($xml->result);
        
            foreach($xml->result as $result) {
                $lat = $result->geometry->location->lat;
                $lng = $result->geometry->location->lng[0];

                $resultCoords = array(
                    'lat' => $lat->__toString(),
                    'lng' => $lng->__toString(),
                );


                return $resultCoords; // return result as soon as we find one
            }
        }
        return null;
    }    
    
    /*
     * From http://stackoverflow.com/q/1502590
     */
    public function distanceBetween($latA, $lngA, $latB, $lngB)
    {
        $R = 6371; // earth's mean radius in km
        $dLat  = $this->rad($latB - $latA);
        $dLong = $this->rad($lngB - $lngA);

        $a = \sin($dLat/2) * \sin($dLat/2) +
              \cos($this->rad($latA)) * \cos($this->rad($latB)) * \sin($dLong/2) * \sin($dLong/2);
        $c = 2 * \atan2(\sqrt($a), \sqrt(1-$a));
        $d = $R * $c;

        return $d;
    }
    
    protected function rad($x)
    {
        return $x * pi()/180;
    }    
}