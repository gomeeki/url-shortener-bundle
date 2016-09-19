<?php

namespace Gomeeki\Bundle\UrlShortenerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class GomeekiUrlShortenerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        // Setup Parameters from Config

        // Host URL Config
        if (isset($config['host_url'])) {
            $container->setParameter('gomeeki_url_shortener.host_url', $config['host_url']);
        }

        // Entity and Repository Class Config
        if (isset($config['classes'])) {
            if (isset($config['classes']['entity'])) {
                $container->setParameter('gomeeki_url_shortener.shorturl.class.entity', $config['classes']['entity']);
            }
            if (isset($config['classes']['factory'])) {
                $container->setParameter('gomeeki_url_shortener.shorturl.class.factory', $config['classes']['factory']);
            }
            if (isset($config['classes']['repository'])) {
                $container->setParameter(
                    'gomeeki_url_shortener.shorturl.class.repository',
                    $config['classes']['repository']
                );
            }
        }

        // Hashids Config
        if (isset($config['hashids'])) {
            if (isset($config['hashids']['salt'])) {
                $container->setParameter('gomeeki_url_shortener.hashids.salt', $config['hashids']['salt']);
            }
            if (isset($config['hashids']['min_length'])) {
                $container->setParameter('gomeeki_url_shortener.hashids.min_length', $config['hashids']['min_length']);
            }
        }
    }
}
