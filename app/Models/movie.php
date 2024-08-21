<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class movie extends Model
{
    use HasFactory,HasUuids;

    protected $table = "movie";
    protected $fillable = ['title','summary','poster','genre_id','year'];

    public function list_cast()
    {
        return $this->hasMany(Cast_Movie::class);
    }

    public function list_reviews()
    {
        return $this->hasMany(Review::class);
    }
}
