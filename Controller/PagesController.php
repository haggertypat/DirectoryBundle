<?php

namespace CCETC\DirectoryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PagesController extends Controller
{
    public function staticAction($template)
    {
        $ua = $_SERVER['HTTP_USER_AGENT'];
        if(preg_match('/\bmsie 6/i', $ua) && !preg_match('/\bopera/i', $ua)) {
            $outdatedBrowser = true;
        } else if(preg_match('/\bmsie 7/i', $ua) && !preg_match('/\bopera/i', $ua)) {    
            $outdatedBrowser = true;
        } else {
            $outdatedBrowser = false;
        }        

        return $this->render($template, array(
            'outdatedBrowser' => $outdatedBrowser
        ));
    }
}
