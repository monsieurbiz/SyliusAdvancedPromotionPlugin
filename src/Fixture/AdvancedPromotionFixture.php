<?php

/*
 * This file is part of Monsieur Biz' Advanced Promotion plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusAdvancedPromotionPlugin\Fixture;

use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use MonsieurBiz\SyliusAdvancedPromotionPlugin\Entity\AfterTaxAwareInterface;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Bundle\FixturesBundle\Fixture\FixtureInterface;
use Sylius\Component\Core\Repository\PromotionRepositoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

final class AdvancedPromotionFixture extends AbstractFixture implements FixtureInterface
{
    public function __construct(
        private PromotionRepositoryInterface $promotionRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function getName(): string
    {
        return 'monsieurbiz_advanced_promotion';
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function load(array $options): void
    {
        $config = $options['promotion_advanced_configuration'] ?? [];
        if (!\is_array($config)) {
            throw new InvalidArgumentException('The "promotion_advanced_configuration" option must be an array.');
        }
        /** @var array $data */
        foreach ($config as $data) {
            if (!isset($data['code'])) {
                continue;
            }
            $promotion = $this->promotionRepository->findOneBy(['code' => $data['code']]);
            if (null === $promotion || !$promotion instanceof AfterTaxAwareInterface) {
                continue;
            }
            $promotion->setAfterTax($data['after_tax'] ?? false);
            $this->entityManager->persist($promotion);
        }

        $this->entityManager->flush();
    }

    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        /** @phpstan-ignore-next-line */
        $optionsNode
            ->children()
                ->arrayNode('promotion_advanced_configuration')
                    ->arrayPrototype()
                    ->children()
                        ->scalarNode('code')->cannotBeEmpty()->end()
                        ->booleanNode('after_tax')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
