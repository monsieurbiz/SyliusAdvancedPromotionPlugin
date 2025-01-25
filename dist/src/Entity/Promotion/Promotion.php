<?php

/*
 * This file is part of Monsieur Biz' Advanced Promotion plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Entity\Promotion;

use Doctrine\ORM\Mapping as ORM;
use MonsieurBiz\SyliusAdvancedPromotionPlugin\Entity\AfterTaxAwareTrait;
use Sylius\Component\Core\Model\Promotion as BasePromotion;
use Sylius\Component\Promotion\Model\PromotionTranslationInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_promotion")
 */
#[ORM\Entity]
#[ORM\Table(name: 'sylius_promotion')]
class Promotion extends BasePromotion implements PromotionInterface
{
    use AfterTaxAwareTrait;

    protected function createTranslation(): PromotionTranslationInterface
    {
        return new PromotionTranslation();
    }
}
