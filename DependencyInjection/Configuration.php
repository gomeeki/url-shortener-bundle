<?php

namespace Gomeeki\Bundle\UrlShortenerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('gomeeki_url_shortener');

        // Configuration Definition
        $rootNode
            ->children()
                ->scalarNode('host_url')->end()
                ->arrayNode('classes')
                    ->children()
                        ->scalarNode('entity')->end()
                        ->scalarNode('factory')->end()
                        ->scalarNode('repository')->end()
                    ->end()
                ->end()
                ->arrayNode('hashids')
                    ->children()
                        ->scalarNode('salt')->end()
                        ->integerNode('min_length')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
