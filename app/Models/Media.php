<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends BaseModel
{
    use SoftDeletes;

    /**
     * @param Media $media
     * @return mixed
     */
    public function next(Media $media)
    {
        return static::where('type', '=', $media->type)
            ->where('id', '>', $this->id)
            ->where('category_id', '=', $media->category_id)
            ->orderBy('id', 'asc')
            ->first();
    }

    /**
     * @param Media $media
     * @return mixed
     */
    public function prev(Media $media)
    {
        return static::where('type', '=', $media->type)
            ->where('id', '<', $this->id)
            ->where('category_id', '=', $media->category_id)
            ->orderBy('id', 'desc')
            ->first();
    }
}
