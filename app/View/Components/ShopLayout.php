<?php

namespace App\View\Components;

use App\Models\product;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ShopLayout extends Component
{

    public $title;
    public $showBreadCrumb;
    /**
     * Create a new component instance.
     */
    public function __construct($title , $showBreadCrumb = true)
    {
        //
        $this->title = $title;
        $this->showBreadCrumb = $showBreadCrumb;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $products = product::active()
        ->latest()
        ->limit(8)
        ->get();
           return view('layouts.shop', [
             'products' => $products,
            ]);
    }
}
