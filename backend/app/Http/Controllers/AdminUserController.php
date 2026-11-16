<?php
namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class AdminUserController {
 public function index(){return response()->json(User::with('roles:id,name,slug')->orderBy('name')->paginate(25));}
 public function store(Request $request) {
  $d=$request->validate(['name'=>'required|string|max:160','username'=>'required|string|max:80|regex:/^[A-Za-z0-9._-]+$/|unique:users,username','role'=>'required|in:teacher,student']);
  $temporary=Str::password(16);
  $u=DB::transaction(function() use($d,$temporary){
   $u=User::create(['name'=>$d['name'],'username'=>mb_strtolower(trim($d['username'])),'password'=>$temporary,'is_active'=>true,'must_change_password'=>true]);
   $u->roles()->attach(Role::where('slug',$d['role'])->firstOrFail()); return $u;
  });
  return response()->json(['user'=>['id'=>$u->id,'name'=>$u->name,'username'=>$u->username,'role'=>$d['role']],'temporary_password'=>$temporary,'warning'=>'این گذرواژه فقط اکنون نمایش داده می‌شود؛ آن را در log ذخیره نکنید.'],201);
 }
 public function update(Request $request, User $user) {
  $d=$request->validate(['name'=>'sometimes|required|string|max:160','is_active'=>'sometimes|boolean']);
  $user->fill($d)->save();
  if(array_key_exists('is_active',$d)&&!$d['is_active']) DB::table('sessions')->where('user_id',$user->id)->delete();
  return response()->json(['message'=>'حساب به‌روزرسانی شد.']);
 }
 public function resetPassword(Request $request, User $user) {
  abort_if($user->id===$request->user()->id,422,'برای حساب مدیر جاری از این مسیر استفاده نکنید.');
  $temporary=Str::password(16);
  DB::transaction(function() use($user,$temporary){
   $user->forceFill(['password'=>$temporary,'must_change_password'=>true])->save();
   DB::table('sessions')->where('user_id',$user->id)->delete();
  });
  return response()->json(['temporary_password'=>$temporary,'message'=>'کاربر باید در ورود بعدی گذرواژه را تغییر دهد.']);
 }
}