<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Student extends Model {
 public $timestamps=false;
 protected $fillable=['user_id','student_number','grade_level'];
 public function user(){return $this->belongsTo(User::class);}
}