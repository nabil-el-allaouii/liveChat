<?php

namespace App\Livewire;

use App\Models\ChatMessage;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Chat extends Component
{
    public $users;
    public $selectedUser;
    public $newMessage;
    public $messages;
    public function mount()
    {
        $this->users = User::where('id', '!=', Auth::user()->id)->latest()->get();
        $this->selectedUser = $this->users->first();
        $this->loadMessages();
    }
    public function loadMessages()
    {
        if(!$this->selectedUser) {
            return;
        }
        $this->messages = ChatMessage::query()->where(function ($query) {
            $query->where('sender_id', Auth::user()->id)
                ->where('receiver_id', $this->selectedUser->id)
                ->orWhere(function ($query) {
                    $query->where('sender_id', $this->selectedUser->id)
                        ->where('receiver_id', Auth::user()->id);
                });
        })->get();
    }
    public function selectUser($userId)
    {
        $this->selectedUser = User::find($userId);
        $this->loadMessages();
    }
    public function submit()
    {
        if (!$this->newMessage) {
            return;
        }
        $message = ChatMessage::create([
            'sender_id' => Auth::user()->id,
            'receiver_id' => $this->selectedUser->id,
            'message' => $this->newMessage,
        ]);
        $this->messages->push($message);
        $this->newMessage = '';
    }
    public function render()
    {
        return view('livewire.chat');
    }
}
