-- ============================================================
-- Algérie Poste – Database Schema
-- Database: traficbureau
-- ============================================================

CREATE DATABASE IF NOT EXISTS `traficbureau`
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE `traficbureau`;

-- Table: bureau
CREATE TABLE IF NOT EXISTS `bureau` (
    `CodeGestionnaire` VARCHAR(50) DEFAULT NULL,
    `CodeBureau` VARCHAR(20) NOT NULL,
    `NomBureau` VARCHAR(100) DEFAULT NULL,
    PRIMARY KEY (`CodeBureau`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: operation
CREATE TABLE IF NOT EXISTS `operation` (
    `CodeOperation` VARCHAR(20) NOT NULL,
    `NomOperation` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`CodeOperation`, `NomOperation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: trafic
CREATE TABLE IF NOT EXISTS `trafic` (
    `CodeBureau` VARCHAR(20) NOT NULL,
    `NomOperation` VARCHAR(100) NOT NULL,
    `DateOperation` VARCHAR(50) NOT NULL,
    `NombreReception` INT DEFAULT 0,
    `MontantReception` DECIMAL(15,2) DEFAULT 0.00,
    `Droit` DECIMAL(15,2) DEFAULT 0.00,
    `REMB` DECIMAL(15,2) DEFAULT 0.00,
    `NBRREMB` INT DEFAULT 0,
    PRIMARY KEY (`CodeBureau`, `NomOperation`, `DateOperation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: carnet
CREATE TABLE IF NOT EXISTS `carnet` (
    `CodeBureau` VARCHAR(20) NOT NULL,
    `Date` VARCHAR(50) NOT NULL,
    `NomBureau` VARCHAR(100) DEFAULT NULL,
    `NombreCarnet` INT DEFAULT 0,
    PRIMARY KEY (`CodeBureau`, `Date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: cnep
CREATE TABLE IF NOT EXISTS `cnep` (
    `CodeOperation` VARCHAR(20) NOT NULL,
    `Date` VARCHAR(50) NOT NULL,
    `Bureau` VARCHAR(100) NOT NULL,
    `NomOperation` VARCHAR(100) DEFAULT NULL,
    `Wilaya` VARCHAR(10) DEFAULT NULL,
    `RPVU` VARCHAR(20) DEFAULT NULL,
    `RIPV` VARCHAR(20) DEFAULT NULL,
    `Montant` DECIMAL(15,2) DEFAULT 0.00,
    PRIMARY KEY (`CodeOperation`, `Date`, `Bureau`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
