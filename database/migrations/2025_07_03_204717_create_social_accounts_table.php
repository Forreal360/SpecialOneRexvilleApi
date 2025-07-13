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
        Schema::create('client_social_accounts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->string('provider'); // facebook, google, apple
            $table->string('provider_user_id'); // ID del usuario en la red social
            $table->string('email')->nullable();
            $table->string('name')->nullable();
            $table->string('avatar')->nullable();
            $table->json('provider_data')->nullable(); // Datos adicionales del proveedor
            $table->datetime('created_at')->useCurrent();
            $table->datetime('updated_at')->useCurrent();

            // Índices para búsquedas eficientes
            $table->unique(['provider', 'provider_user_id']);
            $table->index(['client_id', 'provider']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_social_accounts');
    }
};
