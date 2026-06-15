<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\MainScreen;
use App\Livewire\ComponenteJugador;

Route::get('/', function () {
    return view('welcome');
});

// 1. La página de inicio (Home) que crea la partida automáticamente y te redirige
Route::get('/', function () {
    //$game = Game::createNewGame();
    //return redirect()->route('game.screen', ['code' => $game->code]);
});

// 2. La pantalla de la TV / Monitor (Tablero Público)
Route::get('game/{code}', MainScreen::class);


// Esta es la ruta que tendrá el código QR para los teléfonos
Route::get('sacar-coordenada/{code}', ComponenteJugador::class);