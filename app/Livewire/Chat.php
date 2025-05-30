<?php

namespace App\Livewire;

use App\Models\ChatMessage;
use App\Models\Conversation;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes;
use Illuminate\Support\Facades\Auth;

class Chat extends Component
{
    public $users;
    public $selectedUser;
    public $newMessage;
    public $messages;
    public $conversations;
    public $showCreateConversation = false;
    protected $listeners = ['selectUser'];

    public function mount()
    {
        $this->conversations = collect();
        $this->loadConversations();
        // $this->selectedUser = $this->users->first();
        $this->loadMessages();
    }

    public function loadConversations()
    {
        $this->conversations = Conversation::where('user_one_id', Auth::user()->id)
            ->orWhere('user_two_id', Auth::user()->id)
            ->with(['userOne', 'userTwo'])
            ->get();
    }

    public function loadMessages()
    {
        if (!$this->selectedUser) {
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
        $conversation = Conversation::where('user_one_id', Auth::user()->id)
            ->where('user_two_id', $this->selectedUser->id)
            ->orWhere('user_one_id', $this->selectedUser->id)
            ->where('user_two_id', Auth::user()->id)
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id' => Auth::user()->id,
                'user_two_id' => $this->selectedUser->id,
            ]);
        }


        $message = ChatMessage::create([
            'sender_id' => Auth::user()->id,
            'receiver_id' => $this->selectedUser->id,
            'conversation_id' => $conversation->id,
            'message' => $this->newMessage,
        ]);
        $this->messages->push($message);
        $this->conversations->push($conversation);
        $this->newMessage = '';
    }


    #[Attributes\On('closeCreateConversation')]
    public function handleCloseCreateConversation()
    {
        $this->showCreateConversation = false;
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
