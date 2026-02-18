-- ============================================
-- Script SQL para actualizar los estados de citas
-- De 6 estados a 3 estados: aprobada, reagendada, cancelada
-- ============================================
-- IMPORTANTE: Ejecutar en phpMyAdmin de SiteGround
-- ============================================

-- Paso 1: Actualizar los valores existentes al nuevo esquema
UPDATE `appointments` SET `status` = 'aprobada' WHERE `status` = 'pendiente';
UPDATE `appointments` SET `status` = 'aprobada' WHERE `status` = 'confirmada';
UPDATE `appointments` SET `status` = 'reagendada' WHERE `status` = 'en_proceso';
UPDATE `appointments` SET `status` = 'aprobada' WHERE `status` = 'completada';
UPDATE `appointments` SET `status` = 'cancelada' WHERE `status` = 'no_presentado';
-- 'cancelada' se queda como está

-- Paso 2: Modificar la columna status para usar solo 3 estados
ALTER TABLE `appointments`
MODIFY COLUMN `status` ENUM('aprobada', 'reagendada', 'cancelada') NOT NULL DEFAULT 'aprobada';

-- ============================================
-- Verificación: Ver estados actuales
-- ============================================
SELECT status, COUNT(*) as total
FROM appointments
GROUP BY status;

-- ============================================
-- NOTA: Si algo sale mal, puedes revertir con:
-- ============================================
-- ALTER TABLE `appointments`
-- MODIFY COLUMN `status` ENUM('pendiente', 'confirmada', 'en_proceso', 'completada', 'cancelada', 'no_presentado') NOT NULL DEFAULT 'pendiente';
