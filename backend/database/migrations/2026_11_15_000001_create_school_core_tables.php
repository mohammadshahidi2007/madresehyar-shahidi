<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 20)->unique();
            $table->date('starts_on');
            $table->date('ends_on');
            $table->boolean('is_active')->default(false)->index();
            $table->timestamps();
        });

        Schema::create('classrooms', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('grade');
            $table->string('section', 20);
            $table->timestamps();
            $table->unique(['academic_year_id', 'grade', 'section'], 'classrooms_year_grade_section_unique');
            $table->index(['grade', 'section']);
        });

        Schema::create('students', function (Blueprint $table): void {
            $table->id();
            $table->string('student_code', 30)->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->index(['last_name', 'first_name']);
        });

        Schema::create('subjects', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('name', 120);
            $table->timestamps();
        });

        Schema::create('classroom_student', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->date('enrolled_on');
            $table->date('left_on')->nullable();
            $table->string('status', 20)->default('active');
            $table->timestamps();
            $table->unique(['classroom_id', 'student_id'], 'classroom_student_unique');
            $table->index(['student_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classroom_student');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('students');
        Schema::dropIfExists('classrooms');
        Schema::dropIfExists('academic_years');
    }
};
