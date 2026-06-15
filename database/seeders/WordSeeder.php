<?php

namespace Database\Seeders;

use App\Models\Word;
use Illuminate\Database\Seeder;

class WordSeeder extends Seeder
{
    public function run(): void
    {
        $palabras = [
            'Manzana', 'Avión', 'Fuego', 'Espacio', 'Plátano', 'Computadora', 'Gato', 'Perro',
            'Invierno', 'Fútbol', 'Película', 'Música', 'Guitarra', 'Médico', 'Hospital', 'Escuela',
            'Libro', 'Lápiz', 'Papel', 'Teléfono', 'Cine', 'Playa', 'Montaña', 'Río',
            'Sol', 'Luna', 'Estrella', 'Coche', 'Tren', 'Bicicleta', 'Café', 'Chocolate',
            'Queso', 'Hamburguesa', 'Reloj', 'Llave', 'Dinero', 'Oro', 'Espejo', 'Ventana'
        ];

        foreach ($palabras as $palabra) {
            Word::firstOrCreate(['word' => $palabra]);
        }
    }
}