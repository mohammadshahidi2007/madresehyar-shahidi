<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
class AuditLog extends Model {
 public $timestamps = false;
 protected $fillable = ['actor_user_id','event','subject_user_id','metadata','ip_address','user_agent','created_at'];
 protected function casts(): array { return ['metadata'=>'array','created_at'=>'datetime']; }
 public static function record(Request $request, string $event, ?int $subjectUserId = null, array $metadata = []): void {
  static::create([
   'actor_user_id' => $request->user()?->id,
   'event' => $event,
   'subject_user_id' => $subjectUserId,
   'metadata' => $metadata,
   'ip_address' => $request->ip(),
   'user_agent' => mb_substr((string) $request->userAgent(), 0, 1000),
   'created_at' => now(),
  ]);
 }
}