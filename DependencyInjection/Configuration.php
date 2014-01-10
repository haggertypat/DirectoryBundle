<?php

namespace CCETC\DirectoryBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ccetc_directory');

        $rootNode
            ->children()
                ->scalarNode('bundle_name')->cannotBeEmpty()->end()
                ->scalarNode('bundle_path')->cannotBeEmpty()->end()
                ->scalarNode('title')->cannotBeEmpty()->end()
                ->scalarNode('logo')->defaultvalue(null)->end()
                ->scalarNode('menu_builder')->defaultvalue('CCETCDirectoryBundle:Builder:mainMenu')->end()
                ->scalarNode('layout_template')->defaultvalue('CCETCDirectoryBundle::layout.html.twig')->end()
                ->scalarNode('contact_email')->cannotBeEmpty()->end()
                ->scalarNode('admin_email')->cannotBeEmpty()->end()
                ->scalarNode('copyright')->defaultvalue(null)->end()
                ->scalarNode('og_description')->defaultvalue(null)->end()
                ->scalarNode('og_url')->defaultvalue(null)->end()
                ->scalarNode('google_maps_key')->defaultvalue(null)->end()
                ->scalarNode('google_analytics_account')->defaultvalue(null)->end()
                ->scalarNode('use_profiles')->defaultvalue(true)->end()
                ->scalarNode('use_maps')->defaultvalue(true)->end()
                ->scalarNode('always_show_advanced_search')->defaultvalue(true)->end()
                ->arrayNode('listing_type_config')
                    ->defaultValue(array(
                        array(
                            'admin_service' => 'ccetc.directory.admin.listing',
                            'entity_class_path' => 'replaceUsingBundlePath', // in the DI extension class, we'll look for this and replace in with the bundle path the user has defined
                            'translation_key' => 'listing'
                        )
                    ))
                    ->prototype('array')
                        ->fixXmlConfig('setting')
                        ->children()
                            ->scalarNode('admin_service')->cannotBeEmpty()->end()
                            ->scalarNode('entity_class_path')->cannotBeEmpty()->end()
                            ->scalarNode('translation_key')->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()

            ->end()
        ;

        return $treeBuilder;
    }
}
