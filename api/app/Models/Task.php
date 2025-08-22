<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Veelasky\LaravelHashId\Eloquent\HashableId;

/**
 * @mixin Eloquent
 */
class Task extends Model
{
    use SoftDeletes;
    use HashableId;
    protected $fillable = [
        "title",
        "description",
        "completed_at",
        "user_id",
        "status",
        "project_id",
    ];

    public function user():BelongsTo {
        return $this->belongsTo(User::class, "user_id");
    }

    public function project():BelongsTo {
        return $this->belongsTo(Project::class, "project_id");
    }
}
