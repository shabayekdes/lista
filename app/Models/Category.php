<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * Fillable property
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Set the proper name attribute
     *
     * @param string $value
     */
    public function setNameAttribute($value)
    {
        if (static::whereSlug($slug = Str::slug($value))->exists()) {
            $slug = $this->incrementSlug($slug);
        }

        $this->attributes['name'] = $value;
        $this->attributes['slug'] = $slug;
    }

    /**
     * Get count if slug already exists
     *
     * @param string $slug
     * @return void
     */
    public function incrementSlug($slug)
    {
        $original = $slug;
        $count = static::where('slug', 'LIKE', "$slug%")->count();

        return "{$original}-" . ++$count;
    }
}
