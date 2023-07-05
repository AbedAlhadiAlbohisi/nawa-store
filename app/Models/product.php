<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use NumberFormatter;

class product extends Model
{
    use HasFactory , SoftDeletes ;

     public function category(){

        return $this->belongsTo(category::class , 'category_id');
     }



    // الثوابت
    const STATUS_ACTIVE = 'active';
    const STATUS_DRAFT = 'draft';
    const STATUS_ARCHIVED = 'archived';

     //   الي انا بدي اسمح انها تستخدم في الماس اسايمنت product تحتوي على اسماء الحقول الي بالجدول تبع الfillable تبعت الarrayال
    protected $fillable = [
        'name','slug','category_id','description','short_descripion','price',
        'compare_price','image','status'
    ];

    protected static function booted()
    {
        //قامت دالة booted بتطبيق نطاق عام للاستعلامات
        static::addGlobalScope('owner', function(Builder $query){
            $query->where('user_id','=','1');
        });

    }
    public function categories()
    {
        return $this->BelongsTo(category::class, 'category_id')->withDefault([
            'name'=>'uncategorized',
        ]);
    }

    public function scopeActive(Builder $query){
        $query->where('status', '=' , 'active');
    }

    public function scopeStatus(Builder $query , $status){
        $query->where('status', '=' , $status);
    }

    public static function statusOptions()

    {
        return[
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_DRAFT  => 'Draft',
            self::STATUS_ARCHIVED => 'Archived'
        ];
    }

    public function getImageUrlAttribute(){
        if($this->image){
            return Storage::disk('public')->url($this->image);
        }
        return 'https://placehold.co/600x600';
    }

    public function getNameAttribute($value){
        return ucwords($value);
    }
    public function getPriceFormattedAttribute(){
        $formatter = new NumberFormatter('en',NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($this->price,'USD');
    }
    public function getComparePriceFormattedAttribute(){
        $formatter = new NumberFormatter('en',NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($this->compare_price,'USD');
    }
}
