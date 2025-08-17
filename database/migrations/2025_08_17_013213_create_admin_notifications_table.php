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
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('admin_id')->constrained('admins');
            $table->string('title');
            $table->text('message');
            $table->timestamp('date');
            $table->string('action');
            $table->text('payload');

            $table->enum('read', ['Y', 'N'])->default('N');

            $table->enum('status', ['A', 'I', 'T'])->default('A');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};
