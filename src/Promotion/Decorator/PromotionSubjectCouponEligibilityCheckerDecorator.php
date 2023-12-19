<?php

/*
 * This file is part of Monsieur Biz' Advanced Promotion plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusAdvancedPromotionPlugin\Promotion\Decorator;

use MonsieurBiz\SyliusAdvancedPromotionPlugin\Entity\PromotionCouponsAwareInterface;
use Sylius\Component\Promotion\Checker\Eligibility\PromotionCouponEligibilityCheckerInterface;
use Sylius\Component\Promotion\Checker\Eligibility\PromotionEligibilityCheckerInterface;
use Sylius\Component\Promotion\Model\PromotionInterface;
use Sylius\Component\Promotion\Model\PromotionSubjectInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;

/**
 * @SuppressWarnings(PHPMD.LongClassName)
 */
#[AsDecorator('sylius.promotion_subject_coupon_eligibility_checker')]
final class PromotionSubjectCouponEligibilityCheckerDecorator implements PromotionEligibilityCheckerInterface
{
    public function __construct(
        #[AutowireDecorated]
        private readonly PromotionEligibilityCheckerInterface $promotionSubjectCouponEligibilityChecker,
        #[Autowire('@sylius.promotion_coupon_eligibility_checker')]
        private readonly PromotionCouponEligibilityCheckerInterface $promotionCouponEligibilityChecker
    ) {
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function isEligible(PromotionSubjectInterface $promotionSubject, PromotionInterface $promotion): bool
    {
        if ($this->promotionSubjectCouponEligibilityChecker->isEligible($promotionSubject, $promotion)) {
            return true;
        }

        // Process our custom check if not eligible by decorated checker
        if (!$promotion->isCouponBased()) {
            return true;
        }

        if (!$promotionSubject instanceof PromotionCouponsAwareInterface) {
            return false;
        }

        // Loop on order promotions with coupon to check if one is eligible
        $promotionCoupons = $promotionSubject->getPromotionCoupons();
        foreach ($promotionCoupons as $promotionCoupon) {
            if (!$promotionCoupon || $promotion !== $promotionCoupon->getPromotion()) {
                continue;
            }

            return $this->promotionCouponEligibilityChecker->isEligible($promotionSubject, $promotionCoupon);
        }

        return false;
    }
}
