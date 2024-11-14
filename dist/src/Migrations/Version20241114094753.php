<?php

/*
 * This file is part of Monsieur Biz' Advanced Promotion plugin for Sylius.
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241114094753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monsieurbiz_advanced_promotion_order_promotion_coupon DROP FOREIGN KEY FK_5C4132AF17B24436');
        $this->addSql('ALTER TABLE monsieurbiz_advanced_promotion_order_promotion_coupon DROP FOREIGN KEY FK_5C4132AF8D9F6D38');
        $this->addSql('ALTER TABLE monsieurbiz_advanced_promotion_order_promotion_coupon ADD CONSTRAINT FK_5C4132AF17B24436 FOREIGN KEY (promotion_coupon_id) REFERENCES sylius_promotion_coupon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE monsieurbiz_advanced_promotion_order_promotion_coupon ADD CONSTRAINT FK_5C4132AF8D9F6D38 FOREIGN KEY (order_id) REFERENCES sylius_order (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monsieurbiz_advanced_promotion_order_promotion_coupon DROP FOREIGN KEY FK_5C4132AF8D9F6D38');
        $this->addSql('ALTER TABLE monsieurbiz_advanced_promotion_order_promotion_coupon DROP FOREIGN KEY FK_5C4132AF17B24436');
        $this->addSql('ALTER TABLE monsieurbiz_advanced_promotion_order_promotion_coupon ADD CONSTRAINT FK_5C4132AF8D9F6D38 FOREIGN KEY (order_id) REFERENCES sylius_order (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE monsieurbiz_advanced_promotion_order_promotion_coupon ADD CONSTRAINT FK_5C4132AF17B24436 FOREIGN KEY (promotion_coupon_id) REFERENCES sylius_promotion_coupon (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
