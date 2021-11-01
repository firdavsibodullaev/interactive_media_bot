<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends BaseModel
{
    use SoftDeletes;

    /**
     * @param string $type
     * @return mixed
     */
    public function next(string $type)
    {
        return static::where('type', '=', $type)
            ->where('id', '>', $this->id)
            ->orderBy('id', 'asc')
            ->first();
    }

    /**
     * @param string $type
     * @return mixed
     */
    public function prev(string $type)
    {
        return static::where('type', '=', $type)
            ->where('id', '<', $this->id)
            ->orderBy('id', 'desc')
            ->first();
    }
}
