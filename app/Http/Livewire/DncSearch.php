<?php

namespace App\Http\Livewire;

use Livewire\Component;

class DncSearch extends Component
{
    public $search = '';

    public function render()
    {
        return view('livewire.dnc-search');
    }
}
