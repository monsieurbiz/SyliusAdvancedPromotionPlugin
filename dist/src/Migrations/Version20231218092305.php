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
final class Version20231218092305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE monsieurbiz_advanced_promotion_order_promotion_coupon (order_id INT NOT NULL, promotion_coupon_id INT NOT NULL, INDEX IDX_5C4132AF8D9F6D38 (order_id), INDEX IDX_5C4132AF17B24436 (promotion_coupon_id), PRIMARY KEY(order_id, promotion_coupon_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE monsieurbiz_advanced_promotion_order_promotion_coupon ADD CONSTRAINT FK_5C4132AF8D9F6D38 FOREIGN KEY (order_id) REFERENCES sylius_order (id)');
        $this->addSql('ALTER TABLE monsieurbiz_advanced_promotion_order_promotion_coupon ADD CONSTRAINT FK_5C4132AF17B24436 FOREIGN KEY (promotion_coupon_id) REFERENCES sylius_promotion_coupon (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monsieurbiz_advanced_promotion_order_promotion_coupon DROP FOREIGN KEY FK_5C4132AF8D9F6D38');
        $this->addSql('ALTER TABLE monsieurbiz_advanced_promotion_order_promotion_coupon DROP FOREIGN KEY FK_5C4132AF17B24436');
        $this->addSql('DROP TABLE monsieurbiz_advanced_promotion_order_promotion_coupon');
    }
}
