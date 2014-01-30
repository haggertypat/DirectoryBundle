<?php

namespace CCETC\DirectoryBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader;

use CCETC\DirectoryBundle\Helper\ListingType;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class CCETCDirectoryExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');        
        
        $keys = array(
            'bundle_name',
            'bundle_path',
            'title',
            'logo',
            'menu_builder',
            'layout_template',
            'copyright',
            'contact_email',
            'admin_email',
            'og_description',
            'og_url',
            'google_maps_key',
            'google_analytics_account',
            'always_show_advanced_search',
            'listing_type_config',
            'registration_setting'
        );
        
        foreach($keys as $key)
        {
            // the default values for the default listing_type depends on the value of bundle_path, so look for our "replaceUsingBundlePath" flag and do so if we find it
            if($key == 'listing_type_config') {
                foreach($config['listing_type_config'] as &$singleListingTypeConfig)
                {
                    if($singleListingTypeConfig['entity_class_path'] == "replaceUsingBundlePath") {
                        $singleListingTypeConfig['entity_class_path'] = $config['bundle_path'].'\Entity\Listing';
                    }
                }                
            }
            $container->setParameter('ccetc_directory.'.$key, $config[$key]);
        }
        
    }
}
