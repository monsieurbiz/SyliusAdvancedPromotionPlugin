<?php

/*
 * This file is part of Monsieur Biz' Advanced Promotion plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusAdvancedPromotionPlugin\Promotion\Checker\Eligibility;

use MonsieurBiz\SyliusAdvancedPromotionPlugin\Context\PromotionContextInterface;
use MonsieurBiz\SyliusAdvancedPromotionPlugin\Entity\AfterTaxAwareInterface;
use Sylius\Component\Promotion\Checker\Eligibility\PromotionEligibilityCheckerInterface;
use Sylius\Component\Promotion\Model\PromotionInterface;
use Sylius\Component\Promotion\Model\PromotionSubjectInterface;
use Webmozart\Assert\Assert;

final class PromotionTaxContextEligibilityChecker implements PromotionEligibilityCheckerInterface
{
    public function __construct(
        private PromotionContextInterface $promotionContext
    ) {
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function isEligible(
        PromotionSubjectInterface $promotionSubject,
        PromotionInterface $promotion
    ): bool {
        /** @var AfterTaxAwareInterface $promotion */
        Assert::isInstanceOf($promotion, AfterTaxAwareInterface::class);

        return $this->promotionContext->isAfterTax() === $promotion->isAfterTax();
    }
}
