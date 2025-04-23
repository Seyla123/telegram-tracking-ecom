<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class DashboardWidgets extends Component
{
    public $userCount;

    public function mount()
    {
        $this->userCount = User::count();
    }

    public $count = 1;

    public function increment()
    {
        $this->count++;
    }

    public function decrement()
    {
        $this->count--;
    }
    public function render()
    {
        return view('livewire.dashboard-widgets');
    }
}
