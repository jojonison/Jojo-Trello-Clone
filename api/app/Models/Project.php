<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Veelasky\LaravelHashId\Eloquent\HashableId;

/**
 * @mixin Eloquent
 */
class Project extends Model
{
    use SoftDeletes;
    use HashableId;
    protected $fillable = [
        "project_name",
        "owned_by",

    ];

    public function user():BelongsTo {
        return $this->belongsTo(User::class, "owned_by");
    }
}
