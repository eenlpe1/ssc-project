@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">NOTIFICATIONS</h1>
            <button id="markAllAsRead" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Mark all as read
            </button>
        </div>

        <!-- Notifications List -->
        <div class="bg-white rounded-lg shadow">
            <div id="notificationsList" class="divide-y divide-gray-200">
                @forelse ($notifications as $notification)
                    <div class="notification-item p-4 {{ !$notification->read_at ? 'bg-blue-50' : '' }}" data-id="{{ $notification->id }}">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div class="ml-4 flex-grow">
                                <p class="text-sm font-medium text-gray-900">{{ $notification->data['title'] }}</p>
                                <p class="text-sm text-gray-700">{{ $notification->data['message'] }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                            @if(!$notification->read_at)
                                <button class="mark-as-read-btn ml-4 px-3 py-1 text-sm text-blue-600 hover:text-blue-800 focus:outline-none">
                                    Mark as read
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-gray-500">
                        No notifications
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Function to mark a notification as read
    async function markAsRead(notificationId) {
        try {
            await fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            window.location.reload();
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }

    // Function to mark all notifications as read
    async function markAllNotificationsAsRead() {
        try {
            await fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            window.location.reload();
        } catch (error) {
            console.error('Error marking all notifications as read:', error);
        }
    }

    // Function to refresh notifications
    async function refreshNotifications() {
        try {
            const response = await fetch('/notifications', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            if (response.ok) {
                window.location.reload();
            }
        } catch (error) {
            console.error('Error refreshing notifications:', error);
        }
    }

    // Add click handlers for mark as read buttons
    document.querySelectorAll('.mark-as-read-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            const notificationId = e.target.closest('.notification-item').dataset.id;
            markAsRead(notificationId);
        });
    });

    // Add click handler for mark all as read button
    document.getElementById('markAllAsRead').addEventListener('click', markAllNotificationsAsRead);

    // Auto-refresh notifications every 30 seconds
    setInterval(refreshNotifications, 30000);

    // Force refresh if coming from a page that created a notification
    @if(session('refresh_notifications'))
        refreshNotifications();
    @endif
</script>
@endpush
@endsection 