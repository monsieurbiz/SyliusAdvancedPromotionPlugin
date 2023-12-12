<?php

/*
 * This file is part of Monsieur Biz' Advanced Promotion plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusAdvancedPromotionPlugin\Context;

class PromotionContext implements PromotionContextInterface
{
    protected bool $afterTax = false;

    public function isAfterTax(): bool
    {
        return $this->afterTax ?? false;
    }

    public function setAfterTax(bool $afterTax): void
    {
        $this->afterTax = $afterTax;
    }
}