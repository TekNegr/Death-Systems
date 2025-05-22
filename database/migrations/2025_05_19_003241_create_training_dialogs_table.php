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
        Schema::create('training_dialogs', function (Blueprint $table) {
            $table->id();
            $table->text('user_message');
            $table->text('ai_response');
            $table->text('recovery_answer')->nullable();
            $table->float('score');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_dialogs');
    }
};
