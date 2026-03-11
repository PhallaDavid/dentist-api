<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();

            // Patient info
            $table->string('patient_name');
            $table->date('patient_birth')->nullable(); // Date of Birth
            $table->enum('patient_gender', ['male', 'female', 'other']);
            $table->string('patient_address')->nullable();
            $table->string('patient_phone')->nullable();

            // Treatment info
            $table->string('treatment');
            $table->string('doctor_name');
            $table->dateTime('appointment_at'); // appointment date & time

            // Financial info
            $table->decimal('price', 10, 2)->unsigned();
            $table->decimal('paid_amount', 10, 2)->unsigned();
            $table->decimal('due_amount', 10, 2)->unsigned();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');

            // Medical/Health info
            $table->text('chief_complain')->nullable();
            $table->text('medical_history')->nullable();
            $table->boolean('allergies')->default(false);
            $table->boolean('anesthetic')->default(false);
            $table->boolean('penicillin')->default(false);
            $table->boolean('hemophilia')->default(false);
            $table->boolean('diabetes')->default(false);
            $table->boolean('hypertension')->default(false);
            $table->boolean('hepatitis')->default(false);
            $table->boolean('hiv')->default(false);
            $table->boolean('heart_attack')->default(false);
            $table->boolean('angina')->default(false);
            $table->boolean('bone_disease')->default(false);
            $table->boolean('pregnant')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('treatments');
    }
}