<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Notifications extends Component
{

    public $notification;
    public $unread ;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
        $user =Auth::user();
        $this->notification = $user->notification(); //readnotifications relation
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.notifications');
    }
}
