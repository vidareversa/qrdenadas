<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Cache;

/**
 * MainScreen maneja la logica del tablero
 */
class MainScreen extends Component
{
    public $code;
    public $palabrasColumnas = []; // Reemplazan a A, B, C, D, E
    public $palabrasFilas = [];    // Reemplazan a 1, 2, 3, 4, 5
    public $celdasCompletadas = []; // Aquí guardamos las celdas que hicieron OK

    public function mount($code)
    {
        $this->code = $code;
        $cacheKeyTablero = "tablero_conceptos_{$this->code}";
        $cacheKeyMazo = "mazo_{$this->code}";
        $cacheKeyCompletadas = "completadas_{$this->code}";

        // 1. Generar Palabras de las coordenadas si no existen
        if (!Cache::has($cacheKeyTablero)) {
            $pool = [
                'ISLA', 'VARÓN', 'MUJER', 'GATO', 'SANDÍA', 'AMOR', 'VECINDARIO', 'PIANO',
                'ABRAZO', 'MÚSICA', 'RISAS', 'CIELO', 'CASA', 'PLAYA', 'CHOCOLATE', 'PAZ',
                'CARIÑO', 'BESO', 'MATE', 'VIAJE', 'DULCE', 'SUEÑO', 'LUZ', 'BRILLO'
            ];
            shuffle($pool);

            // 5 filas y 5 columnas
            $setup = [
                'columnas' => array_slice($pool, 0, 5),
                'filas' => array_slice($pool, 5, 5)
            ];

            Cache::put($cacheKeyTablero, $setup, now()->addHours(2));
        }

        $tablero = Cache::get($cacheKeyTablero);
        $this->palabrasColumnas = $tablero['columnas'];
        $this->palabrasFilas = $tablero['filas'];

        // 2. Generar el Mazo Puro de Coordenadas Físicas (Strings: "A1", "B3", etc.)
        if (!Cache::has($cacheKeyMazo)) {
            $mazoInicial = [];
            $letras = ['A', 'B', 'C', 'D', 'E'];
            
            // Cruzamos letras y números para armar combinaciones de texto plano
            foreach ($letras as $letra) {
                foreach (range(1, 5) as $fila) {
                    $mazoInicial[] = "{$letra}{$fila}"; // Esto guarda "A1", "A2", etc.
                }
            }
            
            shuffle($mazoInicial);
            Cache::put($cacheKeyMazo, $mazoInicial, now()->addHours(2));
        }

        // 3. Cargar las celdas que ya se hayan completado en esta partida
        $this->celdasCompletadas = Cache::get($cacheKeyCompletadas, []);
    }

    // REVERB - Antena para escucha en tiempo real cuando un celular manda un ¡OK! a la pista de la coordenada.
    #[On('echo:sala.{code},CeldaCompletada')]
    public function escucharCeldaCompletada($payload)
    {
        $coordenadaCompletada = $payload['coordenada'];
        
        if (!in_array($coordenadaCompletada, $this->celdasCompletadas)) {
            $this->celdasCompletadas[] = $coordenadaCompletada;
            Cache::put("completadas_{$this->code}", $this->celdasCompletadas, now()->addHours(2));
        }
    }

    public function render()
    {
        return view('livewire.main-screen');
    }
}