<?php

/*
 * This file is part of Monsieur Biz' Advanced Promotion plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusAdvancedPromotionPlugin\Promotion\Processor;

use MonsieurBiz\SyliusAdvancedPromotionPlugin\Context\PromotionContextInterface;
use MonsieurBiz\SyliusAdvancedPromotionPlugin\Entity\AfterTaxAwareInterface;
use Sylius\Component\Promotion\Action\PromotionApplicatorInterface;
use Sylius\Component\Promotion\Checker\Eligibility\PromotionEligibilityCheckerInterface;
use Sylius\Component\Promotion\Model\PromotionSubjectInterface;
use Sylius\Component\Promotion\Processor\PromotionProcessorInterface;
use Sylius\Component\Promotion\Provider\PreQualifiedPromotionsProviderInterface;
use Webmozart\Assert\Assert;

final class AfterTaxPromotionProcessor implements PromotionProcessorInterface
{
    public function __construct(
        private PreQualifiedPromotionsProviderInterface $preQualifiedPromotionsProvider,
        private PromotionEligibilityCheckerInterface $promotionEligibilityChecker,
        private PromotionApplicatorInterface $promotionApplicator,
        private PromotionContextInterface $promotionContext
    ) {
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function process(PromotionSubjectInterface $subject): void
    {
        // Set the promotion context to after cart
        $this->promotionContext->setOrderProcessor(true);
        $this->promotionContext->setAfterTax(true);

        /**
         * We do not revert applied promotions because we want to keep the rules applies before tax.
         *
         * @see \Sylius\Component\Promotion\Processor\PromotionProcessor::process()
         */

        // Retrieve promotion after tax, it's a double security because the PromotionTaxContextEligibilityChecker is also called
        $preQualifiedPromotions = $this->getPromotionsAfterTax($subject);

        foreach ($preQualifiedPromotions as $promotion) {
            if ($promotion->isExclusive() && $this->promotionEligibilityChecker->isEligible($subject, $promotion)) {
                $this->promotionApplicator->apply($subject, $promotion);

                return;
            }
        }

        foreach ($preQualifiedPromotions as $promotion) {
            if (!$promotion->isExclusive() && $this->promotionEligibilityChecker->isEligible($subject, $promotion)) {
                $this->promotionApplicator->apply($subject, $promotion);
            }
        }
    }

    private function getPromotionsAfterTax(PromotionSubjectInterface $subject): array
    {
        $preQualifiedPromotions = $this->preQualifiedPromotionsProvider->getPromotions($subject);

        return array_filter($preQualifiedPromotions, function ($promotion) {
            Assert::isInstanceOf($promotion, AfterTaxAwareInterface::class);

            return $promotion->isAfterTax();
        });
    }
}
