<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

/**
 * ComponenteJugador se encarga de gestionar la pantalla e
 * interacción de las cards del jugador al escanear el QR
 */
class ComponenteJugador extends Component
{
    public $code;
    public $coordenada = null; 
    public $estado = 'pendiente'; //estados: 'pendiente', 'votando', 'acertada', 'descartada'
    public $mensaje = '';
    public $tarjetaAsignada = false;

    public $palabraCol = '';
    public $palabraFil = '';

    public function mount($code)
    {
        $this->code = $code;
        $sessionKey = "jugador_celda_{$this->code}"; //con el codigo del juego se escanea

        if (Session::has($sessionKey)) {
            $datos = Session::get($sessionKey);
            $this->coordenada = $datos['coordenada'];
            $this->estado = $datos['estado'] ?? 'pendiente';
            $this->tarjetaAsignada = true;
            $this->mensaje = "⚠️ Tienes una coordenada activa.";
            
            $this->cargarPalabrasAsociadas();
            return;
        }

        $cacheKeyMazo = "mazo_{$this->code}";

        if (!Cache::has($cacheKeyMazo)) {
            $this->mensaje = "La partida no ha iniciado.";
            return;
        }

        $mazo = Cache::get($cacheKeyMazo);

        if (count($mazo) === 0) {
            $this->mensaje = "❌ ¡Mazo vacío! No quedan celdas.";
            return;
        }

        $this->coordenada = array_shift($mazo);
        Cache::put($cacheKeyMazo, $mazo, now()->addHours(2));

        $this->estado = 'pendiente';
        $this->tarjetaAsignada = true;
        $this->mensaje = "🤫 Coordenada secreta recibida.";

        $this->cargarPalabrasAsociadas();
        $this->actualizarSesion();
    }

    /**
     * Busca las palabras correspondientes a los ejes de la coordenada actual
     */
    private function cargarPalabrasAsociadas()
    {
        if (!$this->coordenada) return;

        $letraCol = substr($this->coordenada, 0, 1);
        $numFila = substr($this->coordenada, 1);

        $letras = ['A', 'B', 'C', 'D', 'E'];
        $indexCol = array_search($letraCol, $letras);
        $indexFila = (int)$numFila - 1;

        $palabrasColumnas = Cache::get("sala_columnas_{$this->code}", ['Fuego', 'Agua', 'Tierra', 'Aire', 'Espacio']);
        $palabrasFilas = Cache::get("sala_filas_{$this->code}", ['Perro', 'Gato', 'Árbol', 'Auto', 'Casa']);

        $this->palabraCol = $palabrasColumnas[$indexCol] ?? 'Desconocido';
        $this->palabraFil = $palabrasFilas[$indexFila] ?? 'Desconocido';
    }

    public function marcarAcertada()
    {
        $this->estado = 'acertada';
        $this->actualizarSesion();

        broadcast(new \App\Events\CeldaCompletada($this->code, $this->coordenada))->toOthers();
    }

    public function marcarDescartada()
    {
        $this->estado = 'descartada';
        $this->actualizarSesion();
        // Al descartarse queda fuera del mazo (ya se le hizo el array_shift)
    }

    public function liberarMano()
    {
        Session::forget("jugador_celda_{$this->code}");
        $this->tarjetaAsignada = false;
        $this->coordenada = null;
        $this->palabraCol = '';
        $this->palabraFil = '';
        $this->estado = 'pendiente';
        $this->mensaje = "Mano liberada. ¡Listo para volver a escanear!";
    }

    public function actualizarSesion()
    {
        Session::put("jugador_celda_{$this->code}", [
            'coordenada' => $this->coordenada,
            'estado' => $this->estado,
        ]);
    }

    public function render()
    {
        return view('livewire.componente-jugador')->layout('components.layouts.app');
    }
}