<?php

/*
 * This file is part of Monsieur Biz' Advanced Promotion plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Entity\Promotion;

use MonsieurBiz\SyliusAdvancedPromotionPlugin\Entity\AfterTaxAwareInterface;
use Sylius\Component\Core\Model\PromotionInterface as BasePromotionInterface;

interface PromotionInterface extends BasePromotionInterface, AfterTaxAwareInterface
{
}
