<?php

namespace App\View\Components;

use App\Models\product;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TrendingProducts extends Component
{
    public $products;
    public $title;
    /**
     * Create a new component instance.
     */
    public function __construct($title = 'Product Trending', $count = 4)
    {
        //
        $this->title = $title;
        $this->products =  product::withoutGlobalScope('owner')
        ->with('category')
        ->active()
        ->latest('updated_at')
        ->take($count)
        ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.trending-products');
    }
}
