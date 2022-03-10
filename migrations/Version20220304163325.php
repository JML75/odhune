<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220304163325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP INDEX UNIQ_6EEAA67DBE2F0A35, ADD INDEX IDX_6EEAA67DBE2F0A35 (adresse_livraison_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP INDEX IDX_6EEAA67DBE2F0A35, ADD UNIQUE INDEX UNIQ_6EEAA67DBE2F0A35 (adresse_livraison_id)');
    }
}
