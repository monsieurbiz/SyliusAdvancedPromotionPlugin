<?php

/*
 * This file is part of Monsieur Biz' Advanced Promotion plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusAdvancedPromotionPlugin\Form\Extension;

use Sylius\Bundle\OrderBundle\Form\Type\CartType;
use Sylius\Bundle\PromotionBundle\Form\Type\PromotionCouponToCodeType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CartTypeExtension extends AbstractTypeExtension
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('promotionCoupons', CollectionType::class, [
                'entry_type' => PromotionCouponToCodeType::class,
                'entry_options' => [
                    'attr' => [
                        'form' => 'sylius_cart',
                    ],
                ],
                'required' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'button_add_label' => 'monsieurbiz_sylius_advanced_promotion.coupons.add_coupon',
                'attr' => [
                    'class' => 'monsieurbiz-coupons',
                ],
            ])
        ;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setNormalizer('validation_groups', fn (Options $options, array $validationGroups) => function (FormInterface $form) use ($validationGroups) {
            foreach ($form->get('promotionCoupons') as $promotionCoupon) {
                if ((bool) $promotionCoupon->getNormData()) { // Validate the coupon if it was sent
                    $validationGroups[] = 'monsieurbiz_advanced_promotion_coupon';

                    break;
                }
            }

            return $validationGroups;
        });
    }

    public static function getExtendedTypes(): iterable
    {
        return [
            CartType::class,
        ];
    }
}
