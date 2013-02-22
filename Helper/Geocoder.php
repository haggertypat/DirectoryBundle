<?php

namespace CCETC\DirectoryBundle\Helper;

class Geocoder
{
    protected $googleMapsKey;
    protected $container;
    
    public function __construct($googleMapsKey, $container)
    {
        $this->googleMapsKey = $googleMapsKey;
        $this->container = $container;
    }
    
    public function geocodeAddress($string)
    {
        $adr = urlencode($string);
        $url = "http://maps.google.com/maps/geo?q=".$adr."&output=xml&key=".$this->googleMapsKey;
        $xml = simplexml_load_file($url);
        $status = $xml->Response->Status->code;
        
        if($status == '200') {
            $numResults = count($xml->Response->Placemark);
        
            if($numResults > 1) {
                $this->mailPat($numResults.' results', print_r($xml->Response->Placemark, true));
            }
            
            foreach($xml->Response->Placemark as $node) {
                $coordinates = explode(',', $node->Point->coordinates);
                
                $result = array(
                    'lat' => $coordinates[1],
                    'lng' => $coordinates[0],
                );

                return $result; // return result as soon as we find one
            }
        } else {
            $this->mailPat('no response', 'from request for "'.$string.'"');
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
    
    protected function mailPat($subject, $body)
    {
        $message = \Swift_Message::newInstance()
                ->setSubject('Geocode Debug - '.$subject)
                ->setFrom('noreply@ccetompkins.org')
                ->setTo('haggertypat@gmail.com')
                ->setContentType('text/html')
                ->setbody('<html>'.$body.'</html>')
        ;
        $this->container->get('mailer')->send($message);
    }
}