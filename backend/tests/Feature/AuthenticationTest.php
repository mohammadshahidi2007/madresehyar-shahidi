<?php
namespace Tests\Feature;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class AuthenticationTest extends TestCase {
 use RefreshDatabase;
 protected function setUp(): void {
  parent::setUp();
  foreach([['مدیر مدرسه','admin'],['معلم','teacher'],['دانش‌آموز','student']] as [$name,$slug]) Role::create(['name'=>$name,'slug'=>$slug]);
 }
 public function test_user_can_login_and_read_own_profile(): void {
  $u=User::create(['name'=>'آزمایشی','username'=>'test.teacher','password'=>'A-Strong-Test-Password-123','is_active'=>true,'must_change_password'=>false]);
  $u->roles()->attach(Role::where('slug','teacher')->first());
  $this->postJson('/api/v1/auth/login',['username'=>'TEST.TEACHER','password'=>'A-Strong-Test-Password-123'])->assertOk()->assertJsonPath('user.username','test.teacher');
  $this->getJson('/api/v1/me')->assertOk()->assertJsonPath('user.id',$u->id);
 }
 public function test_inactive_user_cannot_login(): void {
  User::create(['name'=>'غیرفعال','username'=>'inactive.user','password'=>'A-Strong-Test-Password-123','is_active'=>false,'must_change_password'=>false]);
  $this->postJson('/api/v1/auth/login',['username'=>'inactive.user','password'=>'A-Strong-Test-Password-123'])->assertUnprocessable();
 }
 public function test_teacher_cannot_open_admin_endpoint(): void {
  $u=User::create(['name'=>'معلم','username'=>'teacher.one','password'=>'A-Strong-Test-Password-123','is_active'=>true,'must_change_password'=>false]);
  $u->roles()->attach(Role::where('slug','teacher')->first());
  $this->actingAs($u)->getJson('/api/v1/admin/users')->assertForbidden();
 }
}