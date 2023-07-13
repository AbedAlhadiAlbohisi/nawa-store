<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'image',
    ];

    protected $appends = [
        'url',
    ];

    protected $hidden = [
        'image','created_at' , 'updated_at'
    ];

    public function getUrlAttribute()
    {
        return Storage::disk('public')->url($this->image);
    }

    public function product()
    {
        return $this -> BelongsTo(product::class,'')->withDefault([
            'name'=>'no images',
            //'image'=>
        ]);
    }

}
