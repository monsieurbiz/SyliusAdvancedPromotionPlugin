<?php

/*
 * This file is part of Monsieur Biz' Advanced Promotion plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusAdvancedPromotionPlugin\Form\Extension;

use Sylius\Bundle\PromotionBundle\Form\Type\PromotionType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

final class PromotionTypeExtension extends AbstractTypeExtension
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('afterTax', CheckboxType::class, [
                'required' => false,
                'label' => 'monsieurbiz_sylius_advanced_promotion.promotion.applied_after_tax',
            ])
        ;
    }

    public static function getExtendedTypes(): iterable
    {
        return [
            PromotionType::class,
        ];
    }
}
