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
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Promotion\Modifier\OrderPromotionsUsageModifierInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;

#[AsDecorator('sylius.promotion_usage_modifier')]
final class OrderPromotionsUsageModifierDecorator implements OrderPromotionsUsageModifierInterface
{
    public function __construct(
        #[AutowireDecorated]
        private readonly OrderPromotionsUsageModifierInterface $promotionProcessor,
    ) {
    }

    public function increment(OrderInterface $order): void
    {
        $this->promotionProcessor->increment($order);
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
        $this->promotionProcessor->decrement($order);
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
