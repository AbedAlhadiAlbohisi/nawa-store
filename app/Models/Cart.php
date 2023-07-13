<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Cart extends Pivot
{
    use HasFactory;
    use HasUuids; // Uuid بتستخدم نظام ال carts بدي اعرف لارافيل انو عندي حقول في الجدول تبع ال
    protected  $table ='carts';
    protected $fillable =[
        'cookie_id','user_id','product_id','quantity'
    ]; // التي لا تكون فورن كي من الاصل idتنتهي ب cookie_id هو نظام يستخدم عندما يكون في الجدول حقول مثل الUuidال


    public function user(){
        return $this->belongsTo(user::class)->withDefault();
    }
    public function product(){
        return $this->belongsTo(product::class);
    }
}
