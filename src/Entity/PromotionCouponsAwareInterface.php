<?php

/*
 * This file is part of Monsieur Biz' Advanced Promotion plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusAdvancedPromotionPlugin\Entity;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\PromotionCouponInterface;

interface PromotionCouponsAwareInterface
{
    /** @return Collection<array-key, PromotionCouponInterface> */
    public function getPromotionCoupons(): Collection;

    public function hasPromotionCoupon(PromotionCouponInterface $promotionCoupon): bool;

    public function addPromotionCoupon(PromotionCouponInterface $promotionCoupon): void;

    public function removePromotionCoupon(PromotionCouponInterface $promotionCoupon): void;
}
