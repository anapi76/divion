<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240401185737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE azucar (idAzucar INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(25) NOT NULL, descripcion VARCHAR(25) NOT NULL, UNIQUE INDEX UNIQ_1595EA133A909126 (nombre), PRIMARY KEY(idAzucar)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE boca (idBoca INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(15) NOT NULL, descripcion LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_878505233A909126 (nombre), PRIMARY KEY(idBoca)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bodega (idBodega INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, direccion VARCHAR(50) NOT NULL, poblacion VARCHAR(25) DEFAULT NULL, provincia VARCHAR(25) NOT NULL, codPostal VARCHAR(5) DEFAULT NULL, email VARCHAR(50) DEFAULT NULL, telefono VARCHAR(16) DEFAULT NULL, web VARCHAR(50) DEFAULT NULL, idDo INT NOT NULL, INDEX IDX_5CD2A5F61ACF2A1 (idDo), PRIMARY KEY(idBodega)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE color (idColor INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(25) NOT NULL, PRIMARY KEY(idColor)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cuerpo (idCuerpo INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(15) NOT NULL, descripcion LONGTEXT NOT NULL, PRIMARY KEY(idCuerpo)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE denominacion (idDo INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, calificada TINYINT(1) DEFAULT 0 NOT NULL, creacion INT DEFAULT NULL, web VARCHAR(50) DEFAULT NULL, imagen VARCHAR(50) NOT NULL, historia LONGTEXT NOT NULL, descripcion LONGTEXT NOT NULL, tipoVinos LONGTEXT NOT NULL, idRegion INT NOT NULL, INDEX IDX_D860CE2A6C7359EC (idRegion), PRIMARY KEY(idDo)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maduracion (idMaduracion INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(30) NOT NULL, descripcion VARCHAR(50) DEFAULT NULL, PRIMARY KEY(idMaduracion)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE maridaje (idMaridaje INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_8E5E431D3A909126 (nombre), PRIMARY KEY(idMaridaje)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE puntuacion (idPuntuacion INT AUTO_INCREMENT NOT NULL, puntos INT NOT NULL, descripcion LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_ABF67C3F28BAD48F (puntos), PRIMARY KEY(idPuntuacion)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE puntuacion_vino (id INT AUTO_INCREMENT NOT NULL, usuario VARCHAR(25) DEFAULT NULL, comentarios LONGTEXT DEFAULT NULL, idPuntuacion INT NOT NULL, idVino INT NOT NULL, INDEX IDX_AD62C03B290262D5 (idPuntuacion), INDEX IDX_AD62C03B7D98B48D (idVino), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (idRegion INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, PRIMARY KEY(idRegion)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sabor (idSabor INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(15) NOT NULL, descripcion LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_ECF785523A909126 (nombre), PRIMARY KEY(idSabor)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tipo_uva (idTipo INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(15) NOT NULL, UNIQUE INDEX UNIQ_C1D29E993A909126 (nombre), PRIMARY KEY(idTipo)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tipo_vino (idTipo INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(25) NOT NULL, descripcion LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_44A3D563A909126 (nombre), PRIMARY KEY(idTipo)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE uva (idUva INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(25) NOT NULL, descripcion LONGTEXT DEFAULT NULL, idTipo INT NOT NULL, UNIQUE INDEX UNIQ_EEABFC173A909126 (nombre), INDEX IDX_EEABFC173D043D9 (idTipo), PRIMARY KEY(idUva)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE uva_do (id INT AUTO_INCREMENT NOT NULL, idUva INT NOT NULL, idDo INT NOT NULL, INDEX IDX_A459033FCFE3E190 (idUva), INDEX IDX_A459033F61ACF2A1 (idDo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vino (idVino INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(40) NOT NULL, descripcion LONGTEXT NOT NULL, notaCata LONGTEXT NOT NULL, imagen VARCHAR(50) NOT NULL, idColor INT NOT NULL, idAzucar INT DEFAULT NULL, idTipo INT NOT NULL, idMaduracion INT DEFAULT NULL, idBodega INT NOT NULL, idSabor INT DEFAULT NULL, idCuerpo INT DEFAULT NULL, idBoca INT DEFAULT NULL, INDEX IDX_E65EA1371920BF4 (idColor), INDEX IDX_E65EA1376844289 (idAzucar), INDEX IDX_E65EA133D043D9 (idTipo), INDEX IDX_E65EA1320CC5C34 (idMaduracion), INDEX IDX_E65EA1366DC82C5 (idBodega), INDEX IDX_E65EA13FB33C64F (idSabor), INDEX IDX_E65EA13C43EA542 (idCuerpo), INDEX IDX_E65EA13F4785BBD (idBoca), PRIMARY KEY(idVino)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vino_maridaje (id INT AUTO_INCREMENT NOT NULL, idVino INT NOT NULL, idMaridaje INT NOT NULL, INDEX IDX_C44743527D98B48D (idVino), INDEX IDX_C4474352DDFD6946 (idMaridaje), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vino_uva (id INT AUTO_INCREMENT NOT NULL, porcentaje INT DEFAULT NULL, idVino INT NOT NULL, idUva INT NOT NULL, INDEX IDX_BA2BFA0F7D98B48D (idVino), INDEX IDX_BA2BFA0FCFE3E190 (idUva), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bodega ADD CONSTRAINT FK_5CD2A5F61ACF2A1 FOREIGN KEY (idDo) REFERENCES denominacion (idDo)');
        $this->addSql('ALTER TABLE denominacion ADD CONSTRAINT FK_D860CE2A6C7359EC FOREIGN KEY (idRegion) REFERENCES region (idRegion)');
        $this->addSql('ALTER TABLE puntuacion_vino ADD CONSTRAINT FK_AD62C03B290262D5 FOREIGN KEY (idPuntuacion) REFERENCES puntuacion (idPuntuacion)');
        $this->addSql('ALTER TABLE puntuacion_vino ADD CONSTRAINT FK_AD62C03B7D98B48D FOREIGN KEY (idVino) REFERENCES vino (idVino)');
        $this->addSql('ALTER TABLE uva ADD CONSTRAINT FK_EEABFC173D043D9 FOREIGN KEY (idTipo) REFERENCES tipo_uva (idTipo)');
        $this->addSql('ALTER TABLE uva_do ADD CONSTRAINT FK_A459033FCFE3E190 FOREIGN KEY (idUva) REFERENCES uva (idUva)');
        $this->addSql('ALTER TABLE uva_do ADD CONSTRAINT FK_A459033F61ACF2A1 FOREIGN KEY (idDo) REFERENCES denominacion (idDo)');
        $this->addSql('ALTER TABLE vino ADD CONSTRAINT FK_E65EA1371920BF4 FOREIGN KEY (idColor) REFERENCES color (idColor)');
        $this->addSql('ALTER TABLE vino ADD CONSTRAINT FK_E65EA1376844289 FOREIGN KEY (idAzucar) REFERENCES azucar (idAzucar)');
        $this->addSql('ALTER TABLE vino ADD CONSTRAINT FK_E65EA133D043D9 FOREIGN KEY (idTipo) REFERENCES tipo_vino (idTipo)');
        $this->addSql('ALTER TABLE vino ADD CONSTRAINT FK_E65EA1320CC5C34 FOREIGN KEY (idMaduracion) REFERENCES maduracion (idMaduracion)');
        $this->addSql('ALTER TABLE vino ADD CONSTRAINT FK_E65EA1366DC82C5 FOREIGN KEY (idBodega) REFERENCES bodega (idBodega)');
        $this->addSql('ALTER TABLE vino ADD CONSTRAINT FK_E65EA13FB33C64F FOREIGN KEY (idSabor) REFERENCES sabor (idSabor)');
        $this->addSql('ALTER TABLE vino ADD CONSTRAINT FK_E65EA13C43EA542 FOREIGN KEY (idCuerpo) REFERENCES cuerpo (idCuerpo)');
        $this->addSql('ALTER TABLE vino ADD CONSTRAINT FK_E65EA13F4785BBD FOREIGN KEY (idBoca) REFERENCES boca (idBoca)');
        $this->addSql('ALTER TABLE vino_maridaje ADD CONSTRAINT FK_C44743527D98B48D FOREIGN KEY (idVino) REFERENCES vino (idVino)');
        $this->addSql('ALTER TABLE vino_maridaje ADD CONSTRAINT FK_C4474352DDFD6946 FOREIGN KEY (idMaridaje) REFERENCES maridaje (idMaridaje)');
        $this->addSql('ALTER TABLE vino_uva ADD CONSTRAINT FK_BA2BFA0F7D98B48D FOREIGN KEY (idVino) REFERENCES vino (idVino)');
        $this->addSql('ALTER TABLE vino_uva ADD CONSTRAINT FK_BA2BFA0FCFE3E190 FOREIGN KEY (idUva) REFERENCES uva (idUva)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bodega DROP FOREIGN KEY FK_5CD2A5F61ACF2A1');
        $this->addSql('ALTER TABLE denominacion DROP FOREIGN KEY FK_D860CE2A6C7359EC');
        $this->addSql('ALTER TABLE puntuacion_vino DROP FOREIGN KEY FK_AD62C03B290262D5');
        $this->addSql('ALTER TABLE puntuacion_vino DROP FOREIGN KEY FK_AD62C03B7D98B48D');
        $this->addSql('ALTER TABLE uva DROP FOREIGN KEY FK_EEABFC173D043D9');
        $this->addSql('ALTER TABLE uva_do DROP FOREIGN KEY FK_A459033FCFE3E190');
        $this->addSql('ALTER TABLE uva_do DROP FOREIGN KEY FK_A459033F61ACF2A1');
        $this->addSql('ALTER TABLE vino DROP FOREIGN KEY FK_E65EA1371920BF4');
        $this->addSql('ALTER TABLE vino DROP FOREIGN KEY FK_E65EA1376844289');
        $this->addSql('ALTER TABLE vino DROP FOREIGN KEY FK_E65EA133D043D9');
        $this->addSql('ALTER TABLE vino DROP FOREIGN KEY FK_E65EA1320CC5C34');
        $this->addSql('ALTER TABLE vino DROP FOREIGN KEY FK_E65EA1366DC82C5');
        $this->addSql('ALTER TABLE vino DROP FOREIGN KEY FK_E65EA13FB33C64F');
        $this->addSql('ALTER TABLE vino DROP FOREIGN KEY FK_E65EA13C43EA542');
        $this->addSql('ALTER TABLE vino DROP FOREIGN KEY FK_E65EA13F4785BBD');
        $this->addSql('ALTER TABLE vino_maridaje DROP FOREIGN KEY FK_C44743527D98B48D');
        $this->addSql('ALTER TABLE vino_maridaje DROP FOREIGN KEY FK_C4474352DDFD6946');
        $this->addSql('ALTER TABLE vino_uva DROP FOREIGN KEY FK_BA2BFA0F7D98B48D');
        $this->addSql('ALTER TABLE vino_uva DROP FOREIGN KEY FK_BA2BFA0FCFE3E190');
        $this->addSql('DROP TABLE azucar');
        $this->addSql('DROP TABLE boca');
        $this->addSql('DROP TABLE bodega');
        $this->addSql('DROP TABLE color');
        $this->addSql('DROP TABLE cuerpo');
        $this->addSql('DROP TABLE denominacion');
        $this->addSql('DROP TABLE maduracion');
        $this->addSql('DROP TABLE maridaje');
        $this->addSql('DROP TABLE puntuacion');
        $this->addSql('DROP TABLE puntuacion_vino');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE sabor');
        $this->addSql('DROP TABLE tipo_uva');
        $this->addSql('DROP TABLE tipo_vino');
        $this->addSql('DROP TABLE uva');
        $this->addSql('DROP TABLE uva_do');
        $this->addSql('DROP TABLE vino');
        $this->addSql('DROP TABLE vino_maridaje');
        $this->addSql('DROP TABLE vino_uva');
    }
}
