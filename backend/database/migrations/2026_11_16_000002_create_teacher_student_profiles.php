<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('teachers',function(Blueprint $t){$t->id();$t->foreignId('user_id')->unique()->constrained()->restrictOnDelete();$t->string('staff_code',40)->unique();});
  Schema::create('students',function(Blueprint $t){$t->id();$t->foreignId('user_id')->unique()->constrained()->restrictOnDelete();$t->string('student_number',40)->unique();$t->unsignedTinyInteger('grade_level');});
 }
 public function down(): void {Schema::dropIfExists('students');Schema::dropIfExists('teachers');}
};