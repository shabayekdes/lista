<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * Fillable property
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'category_id'];

    /**
     * Set the proper slug attribute
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

    public function incrementSlug($slug)
    {
        $original = $slug;
        $count = static::where('slug', 'LIKE', "$slug%")->count();

        return "{$original}-" . ++$count;
    }
}
