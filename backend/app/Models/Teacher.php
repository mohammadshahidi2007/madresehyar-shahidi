<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Teacher extends Model {
 public $timestamps=false;
 protected $fillable=['user_id','staff_code'];
 public function user(){return $this->belongsTo(User::class);}
}