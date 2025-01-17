@extends('layouts.app')

@section('content')
<div class="p-6 mt-20">
    <!-- Discussion Header -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        @if($discussion->image)
            <div class="relative h-64 w-full">
                <img src="{{ asset('storage/' . $discussion->image) }}" 
                     alt="Agenda image for {{ $discussion->title }}"
                     class="w-full h-full object-cover">
            </div>
        @endif
        <div class="p-6">
            <div class="flex justify-between items-start">
                <div class="space-y-4 flex-1">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $discussion->title }}</h2>
                        <button onclick="closeDiscussion()" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>{{ $discussion->location }}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ $discussion->date->format('F d, Y') }}</span>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 mt-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">Description</h3>
                        <p class="text-gray-600">{{ $discussion->description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Section -->
    <div class="bg-white rounded-lg shadow-lg mt-6">
        <!-- Messages -->
        <div class="h-[500px] overflow-y-auto p-6 space-y-6" id="messages">
            @foreach($messages as $message)
                <div class="flex items-start gap-4 {{ $message->user_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                    <!-- User Avatar -->
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-br {{ $message->user->role === 'admin' ? 'from-red-500 to-pink-500' : 'from-blue-500 to-blue-600' }} rounded-full flex items-center justify-center text-white shadow-md">
                            {{ strtoupper(substr($message->user->name, 0, 1)) }}
                        </div>
                        <div class="text-xs text-center mt-1 font-medium {{ $message->user->role === 'admin' ? 'text-red-600' : 'text-blue-600' }}">
                            {{ ucfirst($message->user->role) }}
                        </div>
                    </div>

                    <!-- Message Content -->
                    <div class="flex-1 {{ $message->user_id === auth()->id() ? 'text-right' : '' }}">
                        <div class="flex items-center gap-2 mb-1 {{ $message->user_id === auth()->id() ? 'justify-end' : '' }}">
                            <span class="font-semibold text-gray-900">{{ $message->user->name }}</span>
                            <span class="text-xs text-gray-500">{{ $message->created_at->format('g:i A') }}</span>
                        </div>
                        <div class="inline-block rounded-lg px-4 py-2 shadow-sm max-w-[80%] 
                            {{ $message->user_id === auth()->id() 
                                ? 'bg-blue-600 text-white rounded-br-none' 
                                : 'bg-gray-100 text-gray-800 rounded-bl-none' }}">
                            {{ $message->message }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Message Input -->
        <div class="p-4 border-t border-gray-100 bg-white">
            <form action="{{ route('discussions.messages.store', $discussion) }}" method="POST" class="flex gap-4">
                @csrf
                <input type="text" 
                       name="message" 
                       class="flex-1 rounded-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 px-4 py-2 text-gray-600" 
                       placeholder="Type your message..." 
                       required>
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2">
                    <span>Send</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7M3 12h18"></path>
                    </svg>
                </button>
            </form>
        </div>

        <!-- End Discussion Button -->
        <div class="p-4 border-t border-gray-100 bg-gray-50 text-center">
            <button onclick="closeDiscussion()" 
                    class="text-gray-600 hover:text-gray-800 transition-colors duration-200 flex items-center justify-center gap-2 mx-auto">
                <span>End Conversation</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
    // Scroll to bottom of messages on load
    document.addEventListener('DOMContentLoaded', function() {
        const messages = document.getElementById('messages');
        messages.scrollTop = messages.scrollHeight;
    });

    function closeDiscussion() {
        window.location.href = "{{ route('discussions.index') }}";
    }
</script>
@endsection 