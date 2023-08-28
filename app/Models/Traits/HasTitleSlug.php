<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 *
 */
trait HasTitleSlug
{
    protected static function bootHasTitleSlug(): void
    {
        static::creating(function (Model $model) {
            if (!$model->slug) {
                $model->slug = $model->generateSlug($model->title);
            }
        });
    }

    /**
     * @param string $title
     * @return string
     */
    protected function generateSlug(string $title): string
    {
        $originalSlug = Str::slug($title);

        $i = 0;
        $slug = $originalSlug;
        while (!$this->isSlugUnique($slug)) {
            $slug = sprintf('%s_%d', $originalSlug, ++$i);
        }

        return $slug;
    }

    /**
     * @param string $slug
     * @return bool
     */
    protected function isSlugUnique(string $slug): bool
    {
        return !static::where('slug', $slug)->exists();
    }
}
