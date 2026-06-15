<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('board_cells', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            
            // Ejes públicos (A, B, C, D o 1, 2, 3, 4) y sus palabras asociadas
            $table->char('axis_x', 1)->nullable(); 
            $table->char('axis_y', 1)->nullable(); 
            $table->string('word_heading')->nullable(); 

            // Coordenadas secretas asignadas a los jugadores (Ej: A3)
            $table->char('secret_x', 1)->nullable(); 
            $table->char('secret_y', 1)->nullable();
            $table->foreignId('player_id')->nullable()->constrained()->onDelete('set null'); 
            
            $table->enum('state', ['available', 'assigned', 'guessed', 'failed'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_cells');
    }
};
