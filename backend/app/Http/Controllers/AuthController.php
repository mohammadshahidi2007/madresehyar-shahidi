<?php
namespace App\Http\Controllers;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
class AuthController {
 public function login(Request $request) {
  $d=$request->validate(['username'=>'required|string|max:80','password'=>'required|string|max:200']);
  $username=mb_strtolower(trim($d['username']));
  if (!Auth::attempt(['username'=>$username,'password'=>$d['password'],'is_active'=>true])) {
   AuditLog::record($request, 'auth.login_failed');
   throw ValidationException::withMessages(['credentials'=>['نام کاربری یا گذرواژه صحیح نیست.']]);
  }
  $request->session()->regenerate();
  $request->user()->forceFill(['last_login_at'=>now()])->save();
  AuditLog::record($request, 'auth.login_succeeded', $request->user()->id);
  return response()->json(['user'=>$this->payload($request->user()),'password_change_required'=>$request->user()->must_change_password]);
 }
 public function logout(Request $request) {
  $userId=$request->user()->id;
  AuditLog::record($request, 'auth.logout', $userId);
  Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken();
  return response()->json(['message'=>'با موفقیت خارج شدید.']);
 }
 public function me(Request $request) { return response()->json(['user'=>$this->payload($request->user())]); }
 public function changePassword(Request $request) {
  $d=$request->validate(['current_password'=>'required|string','password'=>'required|string|min:12|confirmed']);
  if (!Hash::check($d['current_password'],$request->user()->password)) throw ValidationException::withMessages(['current_password'=>['گذرواژه فعلی صحیح نیست.']]);
  $request->user()->forceFill(['password'=>$d['password'],'must_change_password'=>false,'password_changed_at'=>now()])->save();
  AuditLog::record($request, 'auth.password_changed', $request->user()->id);
  $request->session()->regenerate();
  return response()->json(['message'=>'گذرواژه با موفقیت تغییر کرد.']);
 }
 private function payload($u): array { return ['id'=>$u->id,'name'=>$u->name,'username'=>$u->username,'roles'=>$u->roles()->pluck('slug'),'is_active'=>$u->is_active,'must_change_password'=>$u->must_change_password]; }
}