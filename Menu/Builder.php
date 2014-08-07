<?php
namespace CCETC\DirectoryBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public $path;    
    public $mainMenu;    
    
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $listingTypeHelper = $this->container->get('ccetc.directory.helper.listingtypehelper');
        $listingTypes = $listingTypeHelper->getAll();
        
        $this->path = str_replace($this->container->get('request')->getBaseUrl(), '', $this->container->get('request')->getRequestUri());
        $this->path = str_replace(strstr($this->path, '?'), "", $this->path); // remove anything after the first ?
        $this->path = explode('/', $this->path);

        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav');

        $menu->addChild('Home', array('route' => 'home'));

        foreach($listingTypes as $listingType) {
            $label = $this->container->get('translator')->trans(ucfirst($listingType->getTranslationKey()).'s');            
            $menu->addChild($label, array('route' => $listingType->getListingsRouteName(), 'label' => $label));
        }

        $this->mainMenu = $menu;
                
        $this->addPageItems();
        
        return $menu;
    }

    public function correctSubMenuCurrent()
    {
        if(isset($this->path[1]) && $this->path[1] == 'page' && isset($this->path[2])) {
            $route = $this->path[2];
            $pageRepository = $this->container->get('doctrine')->getRepository('CCETCDirectoryBundle:Page');
            $page = $pageRepository->findOneByRoute($this->path[2]);

            if(isset($page) && $page->isChild()) {
                $this->mainMenu[$page->getParent()->getTitle()]->setCurrent(true);
            }
        }
    }    

    public function addPageItems()
    {
        $pageRepository = $this->container->get('doctrine')->getRepository('CCETCDirectoryBundle:Page');
        $pages = $pageRepository->findBy(array('parent' => NULL), array('menuWeight' => 'DESC'));
        
        foreach($pages as $page)
        {
            if($page->isParent()) {
                if($page->getContent()) { // parents with content get included in both menus
                    $this->mainMenu->addChild($page->getMenuLabel(), array(
                        'route' => 'page',
                        'routeParameters' => array(
                            'route' => $page->getRoute()
                        )
                    ));   
                    $this->mainMenu[$page->getMenuLabel()]->addChild($page->getMenuLabel(), array(
                        'route' => 'page',
                        'routeParameters' => array(
                            'route' => $page->getRoute()
                        )
                    ));                    
                } else { // parents without content just link to the first child
                    $children = $page->getChildren();
                    $firstChild = $children[0];

                    $this->mainMenu->addChild($page->getMenuLabel(), array(
                        'route' => 'page',
                        'routeParameters' => array(
                            'route' => $firstChild->getRoute()
                        )
                    )); 
                }
                $this->mainMenu[$page->getMenuLabel()]->setChildrenAttribute('class', 'nav nav-pills nav-stacked');
                foreach($page->getChildren() as $child)
                {
                    $this->mainMenu[$page->getMenuLabel()]->addChild($child->getMenuLabel(), array(
                        'route' => 'page',
                        'routeParameters' => array(
                            'route' => $child->getRoute()
                        )
                    ));
                }

            } else {
                $this->mainMenu->addChild($page->getMenuLabel(), array(
                    'route' => 'page',
                    'routeParameters' => array(
                        'route' => $page->getRoute()
                    )
                ));
            }
        }

        $this->correctSubMenuCurrent();
    }
}