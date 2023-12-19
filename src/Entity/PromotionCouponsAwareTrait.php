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
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\PromotionCouponInterface;

trait PromotionCouponsAwareTrait
{
    /**
     * @ORM\ManyToMany(targetEntity=PromotionCouponInterface::class)
     * @ORM\JoinTable(name="monsieurbiz_advanced_promotion_order_promotion_coupon",
     *     joinColumns={@ORM\JoinColumn(name="order_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="promotion_coupon_id", referencedColumnName="id")}
     * )
     *
     * @var Collection<array-key, PromotionCouponInterface>
     */
    #[ORM\ManyToMany(targetEntity: PromotionCouponInterface::class)]
    #[ORM\JoinTable(name: 'monsieurbiz_advanced_promotion_order_promotion_coupon')]
    #[ORM\JoinColumn(name: 'order_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'promotion_coupon_id', referencedColumnName: 'id')]
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
        return $this->promotionCoupons->contains($promotionCoupon);
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
