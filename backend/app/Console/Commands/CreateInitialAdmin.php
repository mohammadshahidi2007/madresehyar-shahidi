<?php
namespace App\Console\Commands;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
class CreateInitialAdmin extends Command {
 protected $signature='schoolyar:make-admin {username} {name}';
 protected $description='Create the initial administrator using an interactively entered password';
 public function handle(): int {
  $username=mb_strtolower(trim((string)$this->argument('username')));
  if(!preg_match('/^[a-zA-Z0-9._-]{3,80}$/',$username)){ $this->error('Username format is invalid.'); return self::FAILURE; }
  if(User::where('username',$username)->exists()){ $this->error('Username already exists.'); return self::FAILURE; }
  $password=$this->secret('Initial password (at least 12 characters)');
  if(!is_string($password)||mb_strlen($password)<12){$this->error('Password must contain at least 12 characters.');return self::FAILURE;}
  $u=User::create(['name'=>(string)$this->argument('name'),'username'=>$username,'password'=>$password,'is_active'=>true,'must_change_password'=>false]);
  $u->roles()->attach(Role::where('slug','admin')->firstOrFail());
  $this->info('Administrator account created.');
  return self::SUCCESS;
 }
}