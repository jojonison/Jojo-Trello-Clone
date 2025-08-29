<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Veelasky\LaravelHashId\Eloquent\HashableId;

/**
 * @mixin Eloquent
 */
class Task extends Model implements HasMedia
{
    use SoftDeletes;
    use HashableId;
    use InteractsWithMedia;
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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('sticker')
            ->singleFile();
    }
}
