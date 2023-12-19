<?php

/*
 * This file is part of Monsieur Biz' Advanced Promotion plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusAdvancedPromotionPlugin\Provider;

use Doctrine\Common\Collections\ArrayCollection;
use InvalidArgumentException;
use MonsieurBiz\SyliusAdvancedPromotionPlugin\Entity\PromotionCouponsAwareInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\PromotionRepositoryInterface;
use Sylius\Component\Promotion\Model\PromotionSubjectInterface;
use Sylius\Component\Promotion\Provider\PreQualifiedPromotionsProviderInterface;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;

final class ActivePromotionsByChannelProvider implements PreQualifiedPromotionsProviderInterface
{
    public function __construct(private PromotionRepositoryInterface $promotionRepository)
    {
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function getPromotions(PromotionSubjectInterface $subject): array
    {
        if (!$subject instanceof OrderInterface) {
            throw new UnexpectedTypeException($subject, OrderInterface::class);
        }

        $channel = $subject->getChannel();
        if (null === $channel) {
            throw new InvalidArgumentException('Order has no channel, but it should.');
        }

        $promotionCoupons = new ArrayCollection();
        if ($subject instanceof PromotionCouponsAwareInterface) {
            $promotionCoupons = $subject->getPromotionCoupons();
        }

        // We add our condition on 0 promotion coupons
        if (null === $subject->getPromotionCoupon() && 0 === $promotionCoupons->count()) {
            return $this->promotionRepository->findActiveNonCouponBasedByChannel($channel);
        }

        return $this->promotionRepository->findActiveByChannel($channel);
    }
}
