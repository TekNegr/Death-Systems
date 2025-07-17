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
        Schema::create('job_offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('company');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->string('employment_type')->nullable();
            $table->string('salary')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('website')->nullable();
            $table->string('application_link')->nullable();
            $table->string('status')->default('open'); // open, closed, pending
            $table->string('category')->nullable(); // e.g., IT, Marketing, etc.
            // lastApplicationDate 
            $table->date('last_application_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_offers');
    }
};
