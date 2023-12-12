<?php

/*
 * This file is part of Monsieur Biz' Advanced Promotion plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusAdvancedPromotionPlugin\Promotion\Validator;

use MonsieurBiz\SyliusAdvancedPromotionPlugin\Entity\PromotionCouponsAwareInterface;
use Sylius\Component\Promotion\Checker\Eligibility\PromotionEligibilityCheckerInterface;
use Sylius\Component\Promotion\Model\PromotionSubjectInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

final class PromotionSubjectCouponsValidator extends ConstraintValidator
{
    public function __construct(
        #[Autowire('@sylius.promotion_eligibility_checker')]
        private PromotionEligibilityCheckerInterface $promotionEligibilityChecker
    ) {
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        Assert::isInstanceOf($constraint, PromotionSubjectCoupons::class);

        if (!$value instanceof PromotionCouponsAwareInterface) {
            return;
        }

        foreach ($value->getPromotionCoupons() as $promotionCoupon) {
            if (null === ($promotion = $promotionCoupon->getPromotion())) {
                $this->context->buildViolation($constraint->message)->atPath('promotionCoupons')->addViolation();

                continue;
            }

            /** @var PromotionSubjectInterface $value */
            Assert::isInstanceOf($value, PromotionSubjectInterface::class);

            if ($this->promotionEligibilityChecker->isEligible($value, $promotion)) {
                continue;
            }

            $this->context->buildViolation($constraint->message)->atPath('promotionCoupons')->addViolation();
        }
    }
}
