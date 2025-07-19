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
        Schema::create('client_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->date('date');
            $table->string('name');
            $table->datetime('created_at')->useCurrent();
            $table->datetime('updated_at')->useCurrent();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('client_vehicles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_services');
    }
};
