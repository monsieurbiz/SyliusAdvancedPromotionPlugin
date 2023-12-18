<?php

/*
 * This file is part of Monsieur Biz' Advanced Promotion plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusAdvancedPromotionPlugin\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\PromotionCouponInterface;

trait PromotionCouponsAwareTrait
{
    /** @var Collection<array-key, PromotionCouponInterface> */
    private $promotionCoupons;

    private function initializePromotionCoupons(): void
    {
        $this->promotionCoupons = new ArrayCollection();
    }

    /** @return Collection<array-key, ?PromotionCouponInterface> */
    public function getPromotionCoupons(): Collection
    {
        return $this->promotionCoupons;
    }

    public function hasPromotionCoupon(PromotionCouponInterface $promotionCoupon): bool
    {
        foreach ($this->promotionCoupons as $currentPromotionCoupon) {
            if ($currentPromotionCoupon === $promotionCoupon) {
                return true;
            }
        }

        return false;
    }

    public function addPromotionCoupon(PromotionCouponInterface $promotionCoupon): void
    {
        if (!$this->hasPromotionCoupon($promotionCoupon)) {
            $this->promotionCoupons->add($promotionCoupon);
        }
    }

    public function removePromotionCoupon(PromotionCouponInterface $promotionCoupon): void
    {
        if ($this->hasPromotionCoupon($promotionCoupon)) {
            $this->promotionCoupons->removeElement($promotionCoupon);
        }
    }
}
