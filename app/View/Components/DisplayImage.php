<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DisplayImage extends Component
{
    public $src;

    public function __construct($path = null, $folder = 'settings')
    {
        $this->src = $path
            ? asset("storage/{$folder}/{$path}")
            : asset('images/default.png'); // fallback
    }

    public function render()
    {
        return view('components.display-image');
    }
}