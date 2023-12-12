<?php

/*
 * This file is part of Monsieur Biz' Advanced Promotion plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusAdvancedPromotionPlugin\Promotion\Validator;

use Symfony\Component\Validator\Constraint;

final class PromotionSubjectCoupons extends Constraint
{
    public string $message = 'sylius.promotion_coupon.is_invalid';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
