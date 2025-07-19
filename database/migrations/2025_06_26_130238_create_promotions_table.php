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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Título de la promoción
            $table->datetime('start_date'); // Fecha de inicio
            $table->datetime('end_date'); // Fecha de fin
            $table->string('image_path')->nullable(); // URL de la imagen
            $table->string('redirect_url')->nullable(); // URL de redirección
            $table->enum('status', ['A', 'I'])->default('A'); // A=Activo, I=Inactivo

            $table->datetime('created_at')->useCurrent();
            $table->datetime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
