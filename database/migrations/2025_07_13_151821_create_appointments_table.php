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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Link to the clinic service
            $table->foreignId('clinic_service_id')->constrained()->onDelete('cascade');

            // Date and time of the appointment
            $table->dateTime('appointment_datetime');

            // User input fields
            $table->string('surname');
            $table->string('first_name');
            $table->string('middle_initial')->nullable();
            $table->string('email');

            $table->timestamps();
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
