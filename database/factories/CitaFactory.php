<?php

namespace Database\Factories;

use App\Models\Cita;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cita>
 */
class CitaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fec_inicio' => $this->faker->dateTimeBetween('now', '+1 month'),
            'fec_fin' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'estado' => $this->faker->randomElement(['pendiente', 'confirmada', 'cancelada']),
            'observaciones' => $this->faker->sentence(),
            'tipo_consulta_id' => 1,  // O elige un ID existente
            'paciente_id' => 1,  // O elige un ID existente
            'medico_id' => 1,  // O elige un ID existente
            'sala_id' => 1,  // O elige un ID existente
        ];
    }
}
