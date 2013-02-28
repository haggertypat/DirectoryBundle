<?php

namespace CCETC\DirectoryBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader;

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
            'title',
            'logo',
            'menu_builder',
            'layout_template',
            'header_template',
            'footer_template',
            'profile_template',
            'listing_block_template',
            'copyright',
            'contact_email',
            'admin_email',
            'og_description',
            'og_url',
            'google_maps_key',
            'google_analytics_account'
        );
        
        foreach($keys as $key)
        {
            $container->setParameter('ccetc_directory.'.$key, $config[$key]);
        }
        
    }
}
