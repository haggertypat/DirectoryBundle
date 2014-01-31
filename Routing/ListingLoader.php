<?php

namespace CCETC\DirectoryBundle\Routing;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class ListingLoader implements LoaderInterface
{
    private $loaded = false;
    protected $container;
    protected $listingTypes;

    public function __construct($container)
    {
        $this->container = $container;
        $listingTypeHelper = $this->container->get('ccetc.directory.helper.listingtypehelper');
        $this->listingTypes = $listingTypeHelper->getAll();
    }

    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "Listing" loader twice');
        }

        $routes = new RouteCollection();

        foreach($this->listingTypes as $listingType)
        {
            $routeActions = array('listings', 'profile', 'signup', 'generate-locations');

            if($this->container->getParameter('ccetc_directory.registration_setting') != 'none') {
                $routeActions[] = 'edit';
            }

            foreach($routeActions as $action)
            {
                $defaults = array(
                    '_controller' => 'CCETCDirectoryBundle:Directory:'.$action,
                    'listingTypeKey' => $listingType->getKey()
                );
                
                // turn the hyphen delimted actions into CamelCase for retrieving the route values
                $parts = explode('-', $action);
                $parts = array_map('ucfirst', $parts);
                $methodSegment = ucfirst(implode('', $parts));

                $patternMethod = 'get'.$methodSegment.'RoutePattern';
                $nameMethod = 'get'.$methodSegment.'RouteName';


                if($action == "profile" || $action == "edit") {
                    $requirements = array(
                        'id' => '\d+',
                    );
                } else {
                    $requirements = array();
                }

                $route = new Route($listingType->$patternMethod(), $defaults, $requirements);
                $routes->add($listingType->$nameMethod(), $route);

            }
        }

        $this->loaded = true;

        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return 'listing' === $type;
    }

    public function getResolver()
    {
        // needed, but can be blank, unless you want to load other resources
        // and if you do, using the Loader base class is easier (see below)
    }

    public function setResolver(LoaderResolverInterface $resolver)
    {
        // same as above
    }
}