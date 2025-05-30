<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CreateConversation extends Component
{
    public $search = '';
    public $selectedUser ;
    public function createConversation($userId)
    {
        $this->dispatch('selectUser', $userId);
        $this->dispatch('closeCreateConversation');
        $this->dispatch('loadMessages');
    }

    public function render()
    {
            $users = User::where('id', '!=', Auth::user()->id)
                ->where('name', 'like', '%' . $this->search . '%')
                ->get();
        return view('livewire.create-conversation' , [
            'users' => $users
        ]);
    }

    public function selectUser($userId)
    {
        $this->emit('selectUser', $userId);
        $this->dispatch('closeCreateConversation');
    }
}
