<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KnowledgeBase extends Model
{
    protected $table = 'knowledge_base';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'category_id',
        'views',
        'is_active',
    ];

    protected $casts = [
        'views' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Auto-generate slug from title
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('title') && empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });
    }

    // Accessor for excerpt
    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->content), 150);
    }
}
