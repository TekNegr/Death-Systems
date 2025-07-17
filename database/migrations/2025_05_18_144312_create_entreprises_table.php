<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntreprisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entreprises', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('recipient_name')->nullable();
            $table->enum('recipient_gender', ['male', 'female', 'other'])->nullable();
            $table->string('email_to_apply')->unique();
            $table->enum('work_domain', ['Developpement', 'Game Dev', 'AI', 'Other'])->nullable();
            // lastApplicationDate
            $table->date('last_application_date')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entreprises');
    }
}
