<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CreateUserFields extends Component
{
    public string $role = 'user';

    public function render()
    {
        return view('livewire.create-user-fields');
    }
}
