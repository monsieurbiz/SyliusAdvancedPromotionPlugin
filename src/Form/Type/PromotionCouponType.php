<?php

/*
 * This file is part of Monsieur Biz' Advanced Promotion plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusAdvancedPromotionPlugin\Form\Type;

use Sylius\Bundle\PromotionBundle\Form\Type\PromotionCouponToCodeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

final class PromotionCouponType extends AbstractType
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('promotionCoupon', PromotionCouponToCodeType::class, [
                'label' => 'sylius.form.cart.coupon',
                'required' => false,
                'by_reference' => false,
                'attr' => [
                    'form' => 'sylius_cart',
                ],
            ])
        ;
    }
}
