<div class="flex h-screen bg-gray-900">
    <!-- Sidebar - Contacts List -->
    <div class="w-64 bg-gray-800 border-r border-gray-700 flex flex-col">
        <!-- Search Box -->
        <div class="p-4 border-b border-gray-700">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-lg text-white">Contacts</h2>
                <button wire:click="$set('showCreateConversation',true)"
                    class="bg-blue-600 text-white p-1.5 rounded-full hover:bg-blue-700 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </button>
            </div>

            <div class="mt-2 relative">
                <input type="text" placeholder="Search users..."
                    class="w-full px-3 py-2 border border-gray-600 bg-gray-700 rounded-md text-sm text-gray-200 placeholder-gray-400">
            </div>
        </div>

        <!-- Users List -->
        <ul class="flex-1 overflow-y-auto">
            <!-- Example of active user -->
            @foreach ($conversations as $conversation)
                @php
                    $otherUser =
                        $conversation->user_one_id === Auth::id() ? $conversation->userTwo : $conversation->userOne;
                @endphp
                <li
                    class="flex items-center p-4 bg-gray-700 cursor-pointer {{ isset($selectedUser) && $selectedUser->id === $otherUser->id ? 'border-l-4' : '' }} mt-2 border-green-600">
                    <div wire:click="selectUser('{{ $otherUser->id }}')" class="flex-1">
                        <span class="font-medium text-gray-200">{{ $otherUser->name }}</span>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Chat Area -->
    <div class="flex flex-col flex-1">
        <!-- Active Chat State -->
        <div class="h-full flex flex-col">
            <!-- Chat Header -->
            <div class="flex items-center justify-between p-4 bg-gray-800 shadow-md">
                <div>
                    <h2 class="font-semibold text-lg text-gray-200">{{ $selectedUser->name ?? '' }}</h2>
                </div>
                <div>
                    <button class="text-gray-400 hover:text-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Messages -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-900" id="chatMessages">
                <!-- Incoming message -->
                @if (empty($messages))
                    <div class="text-gray-400 text-center">No messages yet</div>
                @else
                    @foreach ($messages as $message)
                        @if ($message->sender_id === $selectedUser->id)
                            <div class="flex items-end">
                                <div class="bg-gray-700 text-gray-200 p-3 rounded-lg shadow-md max-w-xs">
                                    <p class="text-sm">{{ $message->message }}</p>
                                    <span
                                        class="text-xs text-gray-400 float-right">{{ $message->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endif
                        @if ($message->sender_id === auth()->user()->id)
                            <div class="flex items-end justify-end">
                                <div class="bg-blue-600 text-white p-3 rounded-lg shadow-md max-w-xs">
                                    <p class="text-sm">{{ $message->message }}</p>
                                    <span
                                        class="text-xs text-blue-300 float-right">{{ $message->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>

            <!-- Input Area -->
            <form wire:submit="submit()">
                <div class="p-4 bg-gray-800 border-t border-gray-700 flex items-center">
                    <button class="text-gray-400 hover:text-gray-200 mr-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                            </path>
                        </svg>
                    </button>
                    <input wire:model="newMessage" type="text"
                        class="flex-1 border border-gray-600 bg-gray-700 text-gray-200 rounded-full px-4 py-2 focus:outline-none focus:border-blue-500"
                        placeholder="Type a message...">
                    <button class="bg-blue-600 text-white p-2 rounded-full ml-2 hover:bg-blue-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- No Chat Selected State (initially hidden - you'll handle this with your logic) -->
        <div class="hidden flex items-center justify-center h-full bg-gray-900">
            <div class="text-center">
                <svg class="w-16 h-16 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                        d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z">
                    </path>
                </svg>
                <p class="mt-4 text-gray-400">Select a contact to start messaging</p>
            </div>
        </div>
    </div>

    
    @if ($showCreateConversation)
        <div
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-70 transition-opacity duration-300">
            <div class="fixed inset-0" wire:click="$dispatch('closeCreateConversation')"></div>
            <div class="w-full max-w-md mx-4 transform transition-all duration-300 scale-100">
                @livewire('create-conversation')
            </div>
        </div>
    @endif
</div>
