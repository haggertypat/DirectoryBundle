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
        $this->path = str_replace($this->container->get('request')->getBaseUrl(), '', $this->container->get('request')->getRequestUri());
        $this->path = str_replace(strstr($this->path, '?'), "", $this->path); // remove anything after the first ?
        $this->path = explode('/', $this->path);
        
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav');

        $menu->addChild('Home', array('route' => 'home'));
        $menu->addChild('Listings', array('route' => 'listings', 'label' => $this->container->get('translator')->trans('Listings')));
        $menu->addChild('About', array('route' => 'about'));

        $menu->setCurrentUri($this->container->get('request')->getRequestUri());

        $this->mainMenu = $menu;
        
        $this->mainMenuCorrectCurrent();
        
        return $menu;
    }

    public function mainMenuCorrectCurrent()
    {
    }    
}