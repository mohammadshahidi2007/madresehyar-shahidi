<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('users',function(Blueprint $t){$t->id();$t->string('name',160);$t->string('username',80)->unique();$t->string('password');$t->boolean('is_active')->default(true)->index();$t->boolean('must_change_password')->default(false);$t->timestamp('password_changed_at')->nullable();$t->timestamp('last_login_at')->nullable();$t->rememberToken();$t->timestamps();});
  Schema::create('roles',function(Blueprint $t){$t->id();$t->string('name',80);$t->string('slug',40)->unique();});
  Schema::create('user_roles',function(Blueprint $t){$t->foreignId('user_id')->constrained()->cascadeOnDelete();$t->foreignId('role_id')->constrained()->restrictOnDelete();$t->primary(['user_id','role_id']);});
  Schema::create('sessions',function(Blueprint $t){$t->string('id')->primary();$t->foreignId('user_id')->nullable()->index();$t->string('ip_address',45)->nullable();$t->text('user_agent')->nullable();$t->longText('payload');$t->integer('last_activity')->index();});
 }
 public function down(): void {Schema::dropIfExists('sessions');Schema::dropIfExists('user_roles');Schema::dropIfExists('roles');Schema::dropIfExists('users');}
};