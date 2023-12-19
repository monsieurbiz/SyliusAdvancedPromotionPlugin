<?php

/*
 * This file is part of Monsieur Biz' Advanced Promotion plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusAdvancedPromotionPlugin\Promotion\Action;

use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;
use MonsieurBiz\SyliusAdvancedPromotionPlugin\Promotion\Applicator\UnitsPromotionAdjustmentsApplicatorInterface;
use Sylius\Component\Core\Distributor\MinimumPriceDistributorInterface;
use Sylius\Component\Core\Distributor\ProportionalIntegerDistributorInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Promotion\Action\DiscountPromotionActionCommand;
use Sylius\Component\Promotion\Model\PromotionInterface;
use Sylius\Component\Promotion\Model\PromotionSubjectInterface;
use Webmozart\Assert\Assert;

/**
 * @SuppressWarnings(PHPMD.LongClassName)
 */
abstract class AbstractFixedDiscountPromotionActionCommand extends DiscountPromotionActionCommand
{
    public function __construct(
        private ProportionalIntegerDistributorInterface $distributor,
        /** We use our custom UnitsPromotionAdjustmentsApplicatorInterface to be able to filter on a subset of items */
        private UnitsPromotionAdjustmentsApplicatorInterface $unitsPromotionAdjustmentsApplicator,
        private ?MinimumPriceDistributorInterface $minimumPriceDistributor = null,
    ) {
    }

    /**
     * Abstract method to implement to call `executeOnItems` with your items.
     *
     * Example :
     *
     *     public function execute(PromotionSubjectInterface $subject, array $configuration, PromotionInterface $promotion): bool
     *    {
     *        return $this->executeOnItems($subject, $configuration, $promotion, $subject->getMyCustomItems());
     *    }
     */
    abstract public function execute(PromotionSubjectInterface $subject, array $configuration, PromotionInterface $promotion): bool;

    /**
     * This method is the same as Sylius but we filter on given order items.
     *
     * @see \Sylius\Component\Core\Promotion\Action\FixedDiscountPromotionActionCommand::execute()
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    protected function executeOnItems(PromotionSubjectInterface $subject, array $configuration, PromotionInterface $promotion, ?Collection $orderItems = null): bool
    {
        /** @var OrderInterface $subject */
        Assert::isInstanceOf($subject, OrderInterface::class);

        if (!$this->isSubjectValid($subject)) {
            return false;
        }

        // If no given items, promotion is on all items
        // It will have the same behavior as the default UnitsPromotionAdjustmentsApplicator
        // @see \Sylius\Component\Core\Promotion\Applicator\UnitsPromotionAdjustmentsApplicator
        if (null == $orderItems) {
            $orderItems = $subject->getItems();
        }

        $channel = $subject->getChannel();
        $channelCode = $subject->getChannel()?->getCode();
        if (!$channel || !$channelCode || !isset($configuration[$channelCode])) {
            return false;
        }

        try {
            $this->isConfigurationValid($configuration[$channelCode]);
        } catch (InvalidArgumentException) {
            return false;
        }

        $subjectTotal = $this->getSubjectTotal($subject, $promotion);
        $promotionAmount = $this->calculateAdjustmentAmount($subjectTotal, $configuration[$channelCode]['amount']);

        if (0 === $promotionAmount) {
            return false;
        }

        if (null !== $this->minimumPriceDistributor) {
            $splitPromotion = $this->minimumPriceDistributor->distribute($orderItems->toArray(), $promotionAmount, $channel, $promotion->getAppliesToDiscounted());
        } else {
            $itemsTotal = [];
            foreach ($orderItems as $orderItem) {
                /** @var OrderItemInterface $orderItem */
                Assert::isInstanceOf($orderItem, OrderItemInterface::class);

                if ($promotion->getAppliesToDiscounted()) {
                    $itemsTotal[] = $orderItem->getTotal();

                    continue;
                }

                $variant = $orderItem->getVariant();
                if (!$variant || !$variant->getAppliedPromotionsForChannel($channel)->isEmpty()) {
                    $itemsTotal[] = 0;

                    continue;
                }

                $itemsTotal[] = $orderItem->getTotal();
            }

            $splitPromotion = $this->distributor->distribute($itemsTotal, $promotionAmount);
        }

        // We add `$orderItems` as last argument to apply promotion only on these items
        $this->unitsPromotionAdjustmentsApplicator->apply($subject, $promotion, $splitPromotion, $orderItems);

        return true;
    }

    protected function isConfigurationValid(array $configuration): void
    {
        Assert::keyExists($configuration, 'amount');
        Assert::integer($configuration['amount']);
    }

    private function calculateAdjustmentAmount(int $promotionSubjectTotal, int $targetPromotionAmount): int
    {
        return -1 * min($promotionSubjectTotal, $targetPromotionAmount);
    }

    private function getSubjectTotal(OrderInterface $order, PromotionInterface $promotion): int
    {
        return $promotion->getAppliesToDiscounted() ? $order->getPromotionSubjectTotal() : $order->getNonDiscountedItemsTotal();
    }
}
