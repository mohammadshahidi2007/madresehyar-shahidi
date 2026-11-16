<?php
namespace Tests\Feature;
use App\Models\AuditLog;
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
  $this->assertDatabaseHas('audit_logs',['event'=>'auth.login_succeeded','subject_user_id'=>$u->id]);
 }
 public function test_inactive_user_cannot_login(): void {
  User::create(['name'=>'غیرفعال','username'=>'inactive.user','password'=>'A-Strong-Test-Password-123','is_active'=>false,'must_change_password'=>false]);
  $this->postJson('/api/v1/auth/login',['username'=>'inactive.user','password'=>'A-Strong-Test-Password-123'])->assertUnprocessable();
  $this->assertDatabaseHas('audit_logs',['event'=>'auth.login_failed']);
 }
 public function test_teacher_cannot_open_admin_endpoint(): void {
  $u=User::create(['name'=>'معلم','username'=>'teacher.one','password'=>'A-Strong-Test-Password-123','is_active'=>true,'must_change_password'=>false]);
  $u->roles()->attach(Role::where('slug','teacher')->first());
  $this->actingAs($u)->getJson('/api/v1/admin/users')->assertForbidden();
 }
 public function test_student_cannot_open_admin_endpoint(): void {
  $u=User::create(['name'=>'دانش‌آموز','username'=>'student.one','password'=>'A-Strong-Test-Password-123','is_active'=>true,'must_change_password'=>false]);
  $u->roles()->attach(Role::where('slug','student')->first());
  $this->actingAs($u)->getJson('/api/v1/admin/users')->assertForbidden();
 }
 public function test_temporary_password_must_be_changed_before_using_profile(): void {
  $u=User::create(['name'=>'معلم','username'=>'teacher.temp','password'=>'A-Strong-Test-Password-123','is_active'=>true,'must_change_password'=>true]);
  $u->roles()->attach(Role::where('slug','teacher')->first());
  $this->actingAs($u)->getJson('/api/v1/me')->assertForbidden();
  $this->actingAs($u)->postJson('/api/v1/auth/password',['current_password'=>'A-Strong-Test-Password-123','password'=>'Another-Strong-Password-456','password_confirmation'=>'Another-Strong-Password-456'])->assertOk();
  $this->assertDatabaseHas('audit_logs',['event'=>'auth.password_changed','subject_user_id'=>$u->id]);
 }
 public function test_audit_records_never_contain_plaintext_passwords(): void {
  $u=User::create(['name'=>'معلم','username'=>'teacher.audit','password'=>'A-Strong-Test-Password-123','is_active'=>true,'must_change_password'=>false]);
  $u->roles()->attach(Role::where('slug','teacher')->first());
  $this->postJson('/api/v1/auth/login',['username'=>'teacher.audit','password'=>'A-Strong-Test-Password-123'])->assertOk();
  $this->assertStringNotContainsString('A-Strong-Test-Password-123', json_encode(AuditLog::all()->toArray()));
 }
}