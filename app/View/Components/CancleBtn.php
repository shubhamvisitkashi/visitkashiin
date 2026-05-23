<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CancleBtn extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $text = "";
    public $route = "";

    public function __construct($text, $route)
    {
        $this->text = $text;
        $this->route = $route;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.cancle-btn');
    }
}
