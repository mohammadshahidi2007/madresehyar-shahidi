<?php
namespace Database\Seeders;
use App\Models\Role;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder {
 public function run(): void {
  foreach([['مدیر مدرسه','admin'],['معلم','teacher'],['دانش‌آموز','student']] as [$name,$slug]) Role::updateOrCreate(['slug'=>$slug],['name'=>$name]);
 }
}