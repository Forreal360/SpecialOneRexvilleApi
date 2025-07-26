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
        Schema::create('client_password_otp', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable(); // Email del cliente
            $table->string('otp_code', 6); // Código OTP de 6 dígitos
            $table->datetime('expires_at'); // Fecha y hora de expiración
            $table->boolean('is_used')->default(false); // Si el código ya fue usado
            $table->integer('attempts')->default(0); // Intentos de verificación
            $table->datetime('created_at')->useCurrent();
            $table->datetime('updated_at')->useCurrent();

            // Índices para optimizar búsquedas
            $table->index('email');
            $table->index(['email', 'otp_code']);
            $table->index('expires_at');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_password_otp');
    }
};
