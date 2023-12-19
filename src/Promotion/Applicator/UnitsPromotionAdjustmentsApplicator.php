<?php

/*
 * This file is part of Monsieur Biz' Advanced Promotion plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusAdvancedPromotionPlugin\Promotion\Applicator;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Distributor\IntegerDistributorInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;
use Sylius\Component\Order\Model\OrderItemUnitInterface;
use Sylius\Component\Promotion\Model\PromotionInterface;
use Webmozart\Assert\Assert;

/**
 * Same behaviour as the default UnitsPromotionAdjustmentsApplicator
 * But we add the possibility to apply the promotion on a subset of items.
 *
 * @see \Sylius\Component\Core\Promotion\Applicator\UnitsPromotionAdjustmentsApplicator
 */
final class UnitsPromotionAdjustmentsApplicator implements UnitsPromotionAdjustmentsApplicatorInterface
{
    public function __construct(private AdjustmentFactoryInterface $adjustmentFactory, private IntegerDistributorInterface $distributor)
    {
    }

    public function apply(OrderInterface $order, PromotionInterface $promotion, array $adjustmentsAmounts, ?Collection $orderItems = null): void
    {
        // If no given items, promotion is on all items
        // It will have the same behavior as the default UnitsPromotionAdjustmentsApplicator
        // @see \Sylius\Component\Core\Promotion\Applicator\UnitsPromotionAdjustmentsApplicator
        if (null == $orderItems) {
            $orderItems = $order->getItems();
        }

        Assert::eq($orderItems->count(), \count($adjustmentsAmounts));

        $channel = $order->getChannel();
        Assert::isInstanceOf($channel, ChannelInterface::class);

        $count = 0;
        foreach ($orderItems as $item) {
            /** @var OrderItemInterface $item */
            Assert::isInstanceOf($item, OrderItemInterface::class);
            $adjustmentAmount = $adjustmentsAmounts[$count++];
            if (0 === $adjustmentAmount) {
                continue;
            }

            $this->applyAdjustmentsOnItemUnits($item, $promotion, $adjustmentAmount, $channel);
        }
    }

    private function applyAdjustmentsOnItemUnits(
        OrderItemInterface $item,
        PromotionInterface $promotion,
        int $itemPromotionAmount,
        ChannelInterface $channel,
    ): void {
        $splitPromotionAmount = $this->distributor->distribute($itemPromotionAmount, $item->getQuantity());

        $variantMinimumPrice = $item->getVariant()?->getChannelPricingForChannel($channel)?->getMinimumPrice();

        $count = 0;
        foreach ($item->getUnits() as $unit) {
            $promotionAmount = $splitPromotionAmount[$count++];
            if (0 === $promotionAmount) {
                continue;
            }

            $this->addAdjustment(
                $promotion,
                $unit,
                $this->calculate($unit->getTotal(), (int) $variantMinimumPrice, $promotionAmount),
            );
        }
    }

    private function addAdjustment(PromotionInterface $promotion, OrderItemUnitInterface $unit, int $amount): void
    {
        $adjustment = $this->adjustmentFactory
            ->createWithData(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT, (string) $promotion->getName(), $amount)
        ;
        $adjustment->setOriginCode($promotion->getCode());

        $unit->addAdjustment($adjustment);
    }

    private function calculate(int $itemTotal, int $minimumPrice, int $promotionAmount): int
    {
        if ($itemTotal + $promotionAmount <= $minimumPrice) {
            return $minimumPrice - $itemTotal;
        }

        return $promotionAmount;
    }
}
