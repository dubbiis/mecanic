<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use App\Models\Appointment;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los vehículos
        $vehicles = Vehicle::all();

        if ($vehicles->isEmpty()) {
            $this->command->warn('No hay vehículos en la base de datos. Ejecuta VehicleSeeder primero.');
            return;
        }

        $serviceTypes = ['revision', 'reparacion', 'itv', 'diagnostico', 'mantenimiento', 'otro'];
        $statuses = ['pendiente', 'confirmada', 'en_proceso', 'completada', 'cancelada'];

        // Crear citas de ejemplo para cada vehículo
        foreach ($vehicles as $vehicle) {
            // Cita pasada completada
            Appointment::create([
                'vehicle_id' => $vehicle->id,
                'appointment_date' => now()->subDays(30)->setTime(10, 0),
                'service_type' => 'revision',
                'status' => 'completada',
                'description' => 'Revisión periódica de mantenimiento',
                'work_done' => 'Cambio de aceite, filtros y revisión de frenos',
                'estimated_cost' => 120.00,
                'final_cost' => 135.50,
                'estimated_duration' => 90,
                'notes' => 'Todo correcto, siguiente revisión en 6 meses'
            ]);

            // Cita pasada - reparación completada
            Appointment::create([
                'vehicle_id' => $vehicle->id,
                'appointment_date' => now()->subDays(60)->setTime(15, 30),
                'service_type' => 'reparacion',
                'status' => 'completada',
                'description' => 'Reparación de sistema de frenos',
                'work_done' => 'Sustitución de pastillas y discos de freno delanteros',
                'estimated_cost' => 250.00,
                'final_cost' => 280.00,
                'estimated_duration' => 120,
                'notes' => 'Se recomendó revisar amortiguadores en próxima visita'
            ]);

            // Cita próxima confirmada
            Appointment::create([
                'vehicle_id' => $vehicle->id,
                'appointment_date' => now()->addDays(7)->setTime(9, 0),
                'service_type' => 'itv',
                'status' => 'confirmada',
                'description' => 'Inspección técnica de vehículos (ITV)',
                'estimated_cost' => 50.00,
                'estimated_duration' => 60,
                'notes' => 'Cliente confirmó asistencia'
            ]);

            // Cita próxima pendiente
            if (rand(0, 1)) {
                Appointment::create([
                    'vehicle_id' => $vehicle->id,
                    'appointment_date' => now()->addDays(15)->setTime(11, 30),
                    'service_type' => $serviceTypes[array_rand($serviceTypes)],
                    'status' => 'pendiente',
                    'description' => 'Revisión general del vehículo',
                    'estimated_cost' => rand(80, 200) + (rand(0, 99) / 100),
                    'estimated_duration' => rand(60, 180),
                ]);
            }
        }

        // Crear algunas citas adicionales para el primer vehículo (histórico más extenso)
        if ($vehicles->count() > 0) {
            $firstVehicle = $vehicles->first();

            Appointment::create([
                'vehicle_id' => $firstVehicle->id,
                'appointment_date' => now()->subMonths(6)->setTime(14, 0),
                'service_type' => 'diagnostico',
                'status' => 'completada',
                'description' => 'Diagnóstico por ruidos en motor',
                'work_done' => 'Revisión completa del motor, ajuste de correa de distribución',
                'estimated_cost' => 180.00,
                'final_cost' => 195.00,
                'estimated_duration' => 150,
            ]);

            Appointment::create([
                'vehicle_id' => $firstVehicle->id,
                'appointment_date' => now()->subMonths(12)->setTime(10, 30),
                'service_type' => 'itv',
                'status' => 'completada',
                'description' => 'ITV anual',
                'work_done' => 'ITV pasada sin defectos',
                'estimated_cost' => 50.00,
                'final_cost' => 50.00,
                'estimated_duration' => 45,
                'notes' => 'Favorable, próxima ITV en ' . $firstVehicle->itv_date->format('d/m/Y')
            ]);

            // Una cita cancelada
            Appointment::create([
                'vehicle_id' => $firstVehicle->id,
                'appointment_date' => now()->subDays(5)->setTime(16, 0),
                'service_type' => 'mantenimiento',
                'status' => 'cancelada',
                'description' => 'Cambio de neumáticos',
                'estimated_cost' => 300.00,
                'estimated_duration' => 90,
                'notes' => 'Cliente canceló, reprogramar'
            ]);
        }

        $this->command->info('Citas de ejemplo creadas exitosamente.');
    }
}
