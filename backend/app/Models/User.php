<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable {
 use Notifiable;
 protected $fillable=['name','username','password','is_active','must_change_password','password_changed_at','last_login_at'];
 protected $hidden=['password','remember_token'];
 protected function casts(): array { return ['password'=>'hashed','is_active'=>'boolean','must_change_password'=>'boolean','password_changed_at'=>'datetime','last_login_at'=>'datetime']; }
 public function roles(){return $this->belongsToMany(Role::class,'user_roles');}
 public function hasRole(string $role): bool{return $this->roles()->where('slug',$role)->exists();}
}