<?php

namespace CCETC\DirectoryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PagesController extends Controller
{
    public function staticAction($template)
    {
        $outdatedBrowser = false;

        if(isset($_SERVER['HTTP_USER_AGENT'])) {
            $ua = $_SERVER['HTTP_USER_AGENT'];
            
            if(preg_match('/\bmsie 6/i', $ua) && !preg_match('/\bopera/i', $ua)) {
                $outdatedBrowser = true;
            } else if(preg_match('/\bmsie 7/i', $ua) && !preg_match('/\bopera/i', $ua)) {    
                $outdatedBrowser = true;
            }        
        }

        return $this->render($template, array(
            'outdatedBrowser' => $outdatedBrowser
        ));
    }

    public function pageAction($route)
    {
        $pageRepository = $this->getDoctrine()->getRepository('CCETCDirectoryBundle:Page');
        $page = $pageRepository->findOneByRoute($route);
        
        return $this->render('CCETCDirectoryBundle:Pages:page.html.twig', array(
            'page' => $page,
            'pageAdmin' => $this->container->get('ccetc.directory.admin.page'),
            'userIsAdmin' => $this->get('security.context')->isGranted('ROLE_ADMIN')
        ));

    }
}
