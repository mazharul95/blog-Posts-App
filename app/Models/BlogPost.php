<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\DeletedAdminScope;
use Illuminate\Support\Facades\Cache;

class BlogPost extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'content', 'user_id'];

    use HasFactory;

    public function comments()
    {
        return $this->hasMany('App\Models\Comment')->latest();
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    //local Query Scopes
    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public function scopeMostCommented(Builder $query)
    {
        // comments_count
        return $query->withCount('comments')->orderBy('comments_count', 'desc');
    }

    public static function boot()
    {
        static::addGlobalScope(new DeletedAdminScope);
        parent::boot();

        static::deleting(function (BlogPost $blogPost) {
            $blogPost->comments()->delete();
        });

        static::updating(function (BlogPost $blogPost) {
            Cache::forget("blog-post-{$blogPost->id}");
        });

        static::restoring(function (BlogPost $blogPost) {
            $blogPost->comments()->restore();
        });
    }
}
