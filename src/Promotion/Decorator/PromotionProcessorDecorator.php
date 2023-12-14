<?php

/*
 * This file is part of Monsieur Biz' Advanced Promotion plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MonsieurBiz\SyliusAdvancedPromotionPlugin\Promotion\Decorator;

use MonsieurBiz\SyliusAdvancedPromotionPlugin\Context\PromotionContextInterface;
use Sylius\Component\Promotion\Model\PromotionSubjectInterface;
use Sylius\Component\Promotion\Processor\PromotionProcessor;
use Sylius\Component\Promotion\Processor\PromotionProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;

#[AsDecorator('sylius.promotion_processor')]
final class PromotionProcessorDecorator implements PromotionProcessorInterface
{
    public function __construct(
        #[AutowireDecorated]
        private readonly PromotionProcessor $promotionProcessor,
        private readonly PromotionContextInterface $promotionContext,
    ) {
    }

    public function process(PromotionSubjectInterface $subject): void
    {
        $this->promotionContext->setOrderProcessor(true);
        $this->promotionContext->setAfterTax(false);
        $this->promotionProcessor->process($subject);
    }
}
