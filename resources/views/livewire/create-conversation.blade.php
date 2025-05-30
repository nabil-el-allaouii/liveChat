<div class="w-full">
    <div class="bg-gray-800 shadow-lg rounded-lg p-6 border border-gray-700">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-white">Start New Conversation</h3>
            <button 
                wire:click="$dispatch('closeCreateConversation')" 
                class="text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Search Box -->
        <div class="mb-5">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input 
                    type="text" 
                    wire:model.live="search"
                    placeholder="Search users by name..."
                    class="w-full pl-10 pr-4 py-3 border border-gray-600 bg-gray-700 text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>
        </div>

        <!-- Users List -->
        <div class="space-y-3 max-h-80 overflow-y-auto pr-1">
            @forelse($users as $user)
                <div wire:click="createConversation('{{ $user->id }}')"
                    class="flex items-center p-3 border border-gray-600 rounded-lg hover:bg-gray-700 cursor-pointer transition-all transform hover:scale-[1.02] hover:shadow-md">
                    <div class="flex-shrink-0">
                        <img 
                            src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF' }}" 
                            alt="{{ $user->name }}" 
                            class="h-12 w-12 rounded-full border-2 border-gray-600"
                        >
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="font-medium text-gray-200 text-lg">{{ $user->name }}</div>
                        <div class="text-sm text-gray-400">{{ $user->email }}</div>
                    </div>
                    <div class="text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 px-4">
                    @if(empty($search))
                        <svg class="w-16 h-16 mx-auto text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z">
                            </path>
                        </svg>
                        <p class="text-gray-400 text-lg">Start typing to search for users</p>
                    @else
                        <svg class="w-16 h-16 mx-auto text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <p class="text-gray-400 text-lg">No users found matching "<span class="font-medium">{{ $search }}</span>"</p>
                        <button wire:click="$set('search', '')" class="mt-3 text-blue-400 hover:underline">
                            Clear search
                        </button>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
</div>
