-- ============================================
-- SCRIPT PARA SITEGROUND - SISTEMA DE CITAS
-- Ejecutar en phpMyAdmin o MySQL
-- Fecha: 2026-02-16
-- ============================================

-- IMPORTANTE: Reemplaza 'nombre_de_tu_bd' por el nombre real de tu base de datos
-- USE nombre_de_tu_bd;

-- ============================================
-- CREAR TABLA DE CITAS
-- ============================================

CREATE TABLE IF NOT EXISTS `appointments` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `vehicle_id` BIGINT UNSIGNED NOT NULL,
    `appointment_date` DATETIME NOT NULL,

    -- Tipo de servicio
    `service_type` ENUM(
        'revision',
        'reparacion',
        'itv',
        'diagnostico',
        'mantenimiento',
        'otro'
    ) DEFAULT 'revision' NOT NULL,

    -- Estado de la cita
    `status` ENUM(
        'pendiente',
        'confirmada',
        'en_proceso',
        'completada',
        'cancelada',
        'no_presentado'
    ) DEFAULT 'pendiente' NOT NULL,

    -- Información del servicio
    `description` TEXT NULL COMMENT 'Descripción del trabajo a realizar',
    `work_done` TEXT NULL COMMENT 'Trabajo realizado',
    `estimated_cost` DECIMAL(10, 2) NULL COMMENT 'Coste estimado en euros',
    `final_cost` DECIMAL(10, 2) NULL COMMENT 'Coste final en euros',
    `estimated_duration` INT NULL COMMENT 'Duración estimada en minutos',
    `notes` TEXT NULL COMMENT 'Notas adicionales',

    -- Timestamps
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL COMMENT 'Soft delete para mantener histórico',

    -- Clave foránea
    CONSTRAINT `fk_appointments_vehicle`
        FOREIGN KEY (`vehicle_id`)
        REFERENCES `vehicles`(`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    -- Índices para optimizar consultas
    INDEX `idx_appointment_date` (`appointment_date`),
    INDEX `idx_status` (`status`),
    INDEX `idx_service_type` (`service_type`),
    INDEX `idx_vehicle_appointment` (`vehicle_id`, `appointment_date`),
    INDEX `idx_deleted_at` (`deleted_at`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Tabla de citas del taller';

-- ============================================
-- VERIFICAR CREACIÓN
-- ============================================

-- Ver estructura de la tabla creada
-- DESCRIBE appointments;

-- Verificar relaciones
-- SHOW CREATE TABLE appointments;

-- ============================================
-- DATOS DE EJEMPLO (OPCIONAL - COMENTADO)
-- ============================================

-- Descomentar estas líneas si quieres insertar datos de prueba
-- NOTA: Asegúrate de que existen vehículos con los IDs que uses

/*
-- Cita 1: Revisión confirmada para mañana
INSERT INTO appointments (
    vehicle_id,
    appointment_date,
    service_type,
    status,
    description,
    estimated_cost,
    estimated_duration,
    notes
) VALUES (
    1,
    DATE_ADD(NOW(), INTERVAL 1 DAY),
    'revision',
    'confirmada',
    'Revisión de los 10.000 km',
    150.00,
    120,
    'Cliente confirmó por teléfono'
);

-- Cita 2: ITV pendiente
INSERT INTO appointments (
    vehicle_id,
    appointment_date,
    service_type,
    status,
    description,
    estimated_cost,
    estimated_duration
) VALUES (
    2,
    DATE_ADD(NOW(), INTERVAL 3 DAY),
    'itv',
    'pendiente',
    'Pasar ITV',
    50.00,
    60
);

-- Cita 3: Reparación completada (hace 1 mes)
INSERT INTO appointments (
    vehicle_id,
    appointment_date,
    service_type,
    status,
    description,
    work_done,
    estimated_cost,
    final_cost,
    estimated_duration
) VALUES (
    1,
    DATE_SUB(NOW(), INTERVAL 30 DAY),
    'reparacion',
    'completada',
    'Cambio de pastillas de freno',
    'Sustituidas pastillas y discos delanteros',
    180.00,
    195.50,
    90
);

-- Cita 4: Mantenimiento completado (hace 2 meses)
INSERT INTO appointments (
    vehicle_id,
    appointment_date,
    service_type,
    status,
    description,
    work_done,
    estimated_cost,
    final_cost,
    estimated_duration,
    notes
) VALUES (
    2,
    DATE_SUB(NOW(), INTERVAL 60 DAY),
    'mantenimiento',
    'completada',
    'Cambio de aceite y filtros',
    'Aceite 5W30, filtro de aceite, filtro de aire y filtro de polen',
    120.00,
    125.00,
    75,
    'Próximo cambio a los 15.000 km'
);
*/

-- ============================================
-- CONSULTAS ÚTILES PARA VERIFICAR
-- ============================================

-- Ver todas las citas
-- SELECT * FROM appointments ORDER BY appointment_date DESC;

-- Ver citas con información del vehículo
-- SELECT
--     a.id,
--     a.appointment_date,
--     a.service_type,
--     a.status,
--     v.name as cliente,
--     v.car,
--     v.plate,
--     a.estimated_cost,
--     a.final_cost
-- FROM appointments a
-- INNER JOIN vehicles v ON a.vehicle_id = v.id
-- ORDER BY a.appointment_date DESC;

-- Citas pendientes o confirmadas (futuras)
-- SELECT
--     a.*,
--     v.name,
--     v.phone,
--     v.car,
--     v.plate
-- FROM appointments a
-- INNER JOIN vehicles v ON a.vehicle_id = v.id
-- WHERE a.appointment_date > NOW()
-- AND a.status IN ('pendiente', 'confirmada')
-- ORDER BY a.appointment_date ASC;

-- Histórico de un vehículo específico (cambiar vehicle_id)
-- SELECT * FROM appointments
-- WHERE vehicle_id = 1
-- AND deleted_at IS NULL
-- ORDER BY appointment_date DESC;

-- Estadísticas: total facturado por tipo de servicio
-- SELECT
--     service_type as 'Tipo de Servicio',
--     COUNT(*) as 'Total Citas',
--     SUM(final_cost) as 'Total Facturado',
--     AVG(final_cost) as 'Precio Medio'
-- FROM appointments
-- WHERE status = 'completada'
-- AND final_cost IS NOT NULL
-- GROUP BY service_type;

-- ============================================
-- FIN DEL SCRIPT
-- ============================================
