<?php

/*
 * This file is part of Monsieur Biz' Advanced Promotion plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusAdvancedPromotionPlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * @SuppressWarnings(PHPMD.LongClassName)
 */
final class MonsieurBizSyliusAdvancedPromotionExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @inheritdoc
     */
    public function load(array $config, ContainerBuilder $container): void
    {
        $configuration = $this->getConfiguration([], $container);
        if (null !== $configuration) {
            $this->processConfiguration($configuration, $config);
        }
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }

    public function getAlias(): string
    {
        return str_replace('monsieur_biz', 'monsieurbiz', parent::getAlias());
    }

    /**
     * @inheritdoc
     */
    public function prepend(ContainerBuilder $container): void
    {
        $doctrineConfig = $container->getExtensionConfig('doctrine_migrations');
        $container->prependExtensionConfig('doctrine_migrations', [
            'migrations_paths' => array_merge(array_pop($doctrineConfig)['migrations_paths'] ?? [], [
                'MonsieurBiz\SyliusAdvancedPromotionPlugin\Migrations' => '@MonsieurBizSyliusAdvancedPromotionPlugin/Migrations',
            ]),
        ]);
    }
}
