<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_number')->unique();
            $table->string('title');
            $table->string('category')->index();
            $table->string('petitioner_name');
            $table->string('petitioner_contact')->nullable();
            $table->string('respondent_name');
            $table->string('respondent_contact')->nullable();
            $table->date('filing_date')->index();
            $table->dateTime('next_hearing_date')->nullable()->index();
            $table->enum('status', ['filed', 'under_review', 'hearing_scheduled', 'in_progress', 'disposed', 'dismissed'])->default('filed')->index();
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal')->index();
            $table->foreignId('client_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('advocate_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('judge_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('summary')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_cases');
    }
};
