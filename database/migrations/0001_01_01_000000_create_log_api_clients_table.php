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
        Schema::create('logs_api_client', function (Blueprint $table) {
            $table->id();
            $table->string('tag', 45)->nullable();
            $table->string('level', 45)->nullable();
            $table->text('message')->nullable();
            $table->text('context')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('method', 45)->nullable();
            $table->text('url')->nullable();
            $table->text('header')->nullable();
            $table->text('body')->nullable();
            $table->string('created_by')->nullable();
            $table->datetime('created_at')->useCurrent();
            $table->datetime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs_api_client');
    }
};
