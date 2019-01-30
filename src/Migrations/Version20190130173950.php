<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190130173950 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_items CHANGE price price INT DEFAULT 0 NOT NULL, CHANGE cost cost INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE orders CHANGE amount amount INT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_items CHANGE price price NUMERIC(12, 2) DEFAULT \'0.00\' NOT NULL, CHANGE cost cost NUMERIC(20, 2) DEFAULT \'0.00\' NOT NULL');
        $this->addSql('ALTER TABLE orders CHANGE amount amount NUMERIC(20, 2) DEFAULT \'0.00\' NOT NULL');
    }
}
