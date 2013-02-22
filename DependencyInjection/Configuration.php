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
                ->scalarNode('title')->cannotBeEmpty()->end()
                ->scalarNode('logo')->defaultvalue(null)->end()
                ->scalarNode('menu_builder')->defaultvalue('CCETCDirectoryBundle:Builder:mainMenu')->end()
                ->scalarNode('layout_template')->defaultvalue('CCETCDirectoryBundle::layout.html.twig')->end()
                ->scalarNode('header_template')->defaultvalue('CCETCDirectoryBundle::_header.html.twig')->end()
                ->scalarNode('footer_template')->defaultvalue('CCETCDirectoryBundle::_footer.html.twig')->end()
                ->scalarNode('profile_template')->defaultvalue('CCETCDirectoryBundle:Directory:profile.html.twig')->end()
                ->scalarNode('listing_block_template')->defaultvalue('CCETCDirectoryBundle:Directory:_listing_block.html.twig')->end()
                ->scalarNode('contact_email')->cannotBeEmpty()->end()
                ->scalarNode('admin_email')->cannotBeEmpty()->end()
                ->scalarNode('copyright')->defaultvalue(null)->end()
                ->scalarNode('og_description')->defaultvalue(null)->end()
                ->scalarNode('og_url')->defaultvalue(null)->end()
                ->scalarNode('google_maps_key')->defaultvalue(null)->end()
                ->scalarNode('google_analytics_account')->defaultvalue(null)->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
