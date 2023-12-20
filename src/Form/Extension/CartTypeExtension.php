<?php

/*
 * This file is part of Monsieur Biz' Advanced Promotion plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusAdvancedPromotionPlugin\Form\Extension;

use MonsieurBiz\SyliusAdvancedPromotionPlugin\Entity\PromotionCouponsAwareInterface;
use Sylius\Bundle\OrderBundle\Form\Type\CartType;
use Sylius\Bundle\PromotionBundle\Form\Type\PromotionCouponToCodeType;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PromotionCouponInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Promotion\Checker\Eligibility\PromotionEligibilityCheckerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class CartTypeExtension extends AbstractTypeExtension
{
    public function __construct(
        #[Autowire('@sylius.promotion_eligibility_checker')]
        private PromotionEligibilityCheckerInterface $promotionEligibilityChecker,
        private CartContextInterface $cartContext
    ) {
    }

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
                    'constraints' => [
                        new Assert\Callback(
                            [$this, 'validatePromotionEntry'],
                            ['monsieurbiz_advanced_promotion_coupon']
                        ),
                    ],
                ],
                'required' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'button_add_label' => 'monsieurbiz_sylius_advanced_promotion.coupons.add_coupon',
                'attr' => [
                    'class' => 'monsieurbiz-coupons',
                ],
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'removeEmptyCouponsOnPreSubmit'])
        ;
    }

    public function validatePromotionEntry(?PromotionCouponInterface $entry, ExecutionContextInterface $context): void
    {
        if (null === $entry || null === ($promotion = $entry->getPromotion())) {
            $context
                ->buildViolation('sylius.promotion_coupon.is_invalid')
                ->addViolation()
            ;

            return;
        }

        // Check if the promotion rule is eligible
        /** @var OrderInterface $order */
        $order = $this->cartContext->getCart();
        if (!$this->promotionEligibilityChecker->isEligible($order, $promotion)) {
            $context
                ->buildViolation('sylius.promotion_coupon.is_invalid')
                ->addViolation()
            ;
        }
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function removeEmptyCouponsOnPreSubmit(FormEvent $event): void
    {
        $formData = $event->getData();
        if (!\is_array($formData)) {
            return;
        }

        $promotionCoupons = array_filter($formData['promotionCoupons'] ?? []);
        if (!empty($promotionCoupons)) {
            return;
        }

        $order = $event->getForm()->getNormData();
        if (!$order instanceof PromotionCouponsAwareInterface) {
            return;
        }

        foreach ($order->getPromotionCoupons() as $promotionCoupon) {
            if (!$promotionCoupon) {
                continue;
            }
            $order->removePromotionCoupon($promotionCoupon);
        }
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setNormalizer('validation_groups', fn (Options $options, array $validationGroups) => function (FormInterface $form) use ($validationGroups) {
            // Keep Sylius validation groups, if someone disable the multiple coupon feature
            // @see \Sylius\Bundle\CoreBundle\Form\Extension\CartTypeExtension
            if ((bool) $form->get('promotionCoupon')->getNormData()) { // Validate the coupon if it was sent
                $validationGroups[] = 'sylius_promotion_coupon';
            }

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
