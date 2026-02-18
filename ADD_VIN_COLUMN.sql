-- ============================================
-- Script SQL para añadir columna VIN (Número de Bastidor)
-- a la tabla vehicles
-- ============================================
-- IMPORTANTE: Ejecutar en phpMyAdmin de SiteGround
-- ============================================

-- Añadir columna 'vin' después de 'plate'
ALTER TABLE `vehicles`
ADD COLUMN `vin` VARCHAR(17) NULL DEFAULT NULL AFTER `plate`;

-- ============================================
-- Verificación: Ver estructura de la tabla
-- ============================================
DESCRIBE vehicles;

-- ============================================
-- NOTA: Si necesitas revertir este cambio:
-- ============================================
-- ALTER TABLE `vehicles` DROP COLUMN `vin`;
