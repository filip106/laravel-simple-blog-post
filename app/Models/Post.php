<?php

namespace App\Models;

use App\Models\Traits\HasTitleSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int    $id
 * @property string $title
 * @property string $content
 * @property string $slug
 * @property int    $author_id
 */
class Post extends Model
{
    use HasFactory, HasTitleSlug;

    protected $fillable = [
        'title',
        'content',
        'slug',
        'author_id',
    ];

    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
