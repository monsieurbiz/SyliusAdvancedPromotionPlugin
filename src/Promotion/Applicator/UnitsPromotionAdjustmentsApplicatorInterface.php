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
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Promotion\Model\PromotionInterface;

/**
 * @SuppressWarnings(PHPMD.LongClassName)
 */
interface UnitsPromotionAdjustmentsApplicatorInterface
{
    /**
     * @param array|int[] $adjustmentsAmounts
     */
    public function apply(OrderInterface $order, PromotionInterface $promotion, array $adjustmentsAmounts, ?Collection $orderItems = null): void;
}
