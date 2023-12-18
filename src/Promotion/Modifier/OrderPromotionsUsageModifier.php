<?php

/*
 * This file is part of Monsieur Biz' Advanced Promotion plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusAdvancedPromotionPlugin\Promotion\Modifier;

use MonsieurBiz\SyliusAdvancedPromotionPlugin\Entity\PromotionCouponsAwareInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Promotion\Modifier\OrderPromotionsUsageModifierInterface;

final class OrderPromotionsUsageModifier implements OrderPromotionsUsageModifierInterface
{
    public function increment(OrderInterface $order): void
    {
        if (!$order instanceof PromotionCouponsAwareInterface) {
            return;
        }

        // Same as Sylius but with multiple coupons
        // @see Sylius\Component\Core\Promotion\Modifier\OrderPromotionsUsageModifier
        foreach ($order->getPromotionCoupons() as $promotionCoupon) {
            if (!$promotionCoupon) {
                continue;
            }
            $promotionCoupon->incrementUsed();
        }
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function decrement(OrderInterface $order): void
    {
        if (!$order instanceof PromotionCouponsAwareInterface) {
            return;
        }

        // Same as Sylius but with multiple coupons
        // @see Sylius\Component\Core\Promotion\Modifier\OrderPromotionsUsageModifier
        foreach ($order->getPromotionCoupons() as $promotionCoupon) {
            if (!$promotionCoupon || OrderInterface::STATE_CANCELLED === $order->getState() && !$promotionCoupon->isReusableFromCancelledOrders()) {
                continue;
            }

            $promotionCoupon->decrementUsed();
        }
    }
}
