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
        Schema::table('appointments', function (Blueprint $table) {
            // Add new columns
            $table->dateTime('appointment_datetime')->after('service_id');
            $table->string('timezone', 50)->after('appointment_datetime')->default('UTC');

            // Drop old columns
            $table->dropColumn(['appointment_date', 'appointment_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Add back old columns
            $table->date('appointment_date')->after('service_id');
            $table->time('appointment_time')->after('appointment_date');

            // Drop new columns
            $table->dropColumn(['appointment_datetime', 'timezone']);
        });
    }
};
