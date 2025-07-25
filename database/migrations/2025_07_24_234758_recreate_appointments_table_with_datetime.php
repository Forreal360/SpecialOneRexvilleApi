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
        // Drop the existing table
        Schema::dropIfExists('appointments');

        // Create the new table with the updated structure
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('service_id');
            $table->dateTime('appointment_datetime');
            $table->string('timezone', 50)->default('UTC');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('client_vehicles')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('vehicle_services')->onDelete('cascade');

            $table->index(['client_id', 'appointment_datetime']);
            $table->index(['appointment_datetime']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
