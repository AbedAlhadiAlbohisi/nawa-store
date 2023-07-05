<?php

namespace App\Http\Controllers;

use App\Models\product;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    //
    public function index(){

    }

    public function show($slug){

        $product= product::active()
        ->withoutGlobalScope('owner')
        ->where('slug','=',$slug)
        ->firstorfail();
        $gallery=ProductImage::where('product_id','=',$product->id)
        ->get();
        return view ('shop.products.show',[
            'product'=> $product,
            'gallery'=>$gallery,
        ]);

    }
}
