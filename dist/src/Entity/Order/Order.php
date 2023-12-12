<?php

/*
 * This file is part of Monsieur Biz' Advanced Promotion plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Entity\Order;

use Doctrine\ORM\Mapping as ORM;
use MonsieurBiz\SyliusAdvancedPromotionPlugin\Entity\PromotionCouponsAwareTrait;
use Sylius\Component\Core\Model\Order as BaseOrder;
use Sylius\Component\Promotion\Model\PromotionCouponInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_order")
 */
#[ORM\Entity]
#[ORM\Table(name: 'sylius_order')]
class Order extends BaseOrder implements OrderInterface
{
    use PromotionCouponsAwareTrait;

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

    public function __construct()
    {
        parent::__construct();
        $this->initializePromotionCoupons();
    }
}
