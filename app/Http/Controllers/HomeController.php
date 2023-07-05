<?php

namespace App\Http\Controllers;

use App\Models\product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $products =  product::withoutGlobalScope('owner')
        ->active()
        ->latest()
        ->take(8)
        ->get();

        return view('shop.home');
    }
}
