<x-app-layout>
    <div class="min-h-screen flex flex-col items-center justify-center sm:justify-center py-6">
        <div class="w-full max-w-xl">
            <div class="flex flex-col mb-12">
                @if (auth()->user()->isAdmin)
                    <x-input-label for="users" :value="__('Filter By Date Or User')" />
                    <form method="GET" action="{{ route('ticket.filter') }}">
                        @csrf
                        <div class="flex space-x-4 w-full">
                            <x-select-list name="user_id" id="user_select" class="w-full">
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </x-select-list>
                            <x-select-list name="status" id="status_select" class="w-full">
                                <option value="newest" {{ request('status') == 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="oldest" {{ request('status') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                            </x-select-list>
                        </div>

                        <div class="mt-4">
                            <x-select-list name="status_filter" id="status_filter_select" class="w-full">
                                <option value="all" {{ request('status_filter') == 'all' ? 'selected' : '' }}>All</option>
                                <option value="today" {{ request('status_filter') == 'today' ? 'selected' : '' }}>Due Today</option>
                                <option value="expired" {{ request('status_filter') == 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="under_process" {{ request('status_filter') == 'under_process' ? 'selected' : '' }}>Under Process</option>
                            </x-select-list>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="bg-white rounded-lg p-2">Filter</button>
                        </div>
                    </form>
                @endif
            </div>

            <div class="flex flex-col items-center">
                <div class="flex justify-between w-full mb-4">
                    <h1 class="text-white text-lg font-bold">Support Tickets</h1>
                    <div>
                        <a href="{{ route('ticket.create') }}" class="bg-white rounded-lg p-2">Create New</a>
                    </div>
                </div>

                <div class="w-full px-4 py-3 bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg">
                    @forelse ($tickets as $ticket)
                        <div class="text-white flex justify-between py-2">
                            <a href="{{ route('ticket.show', $ticket->id) }}">{{ $ticket->title }}</a>
                            @if($ticket->due_date == now()->format('Y/m/d'))
                                <p class="text-yellow-600"> Due Date: Today</p>
                            @elseif($ticket->due_date < now()->format('Y/m/d'))
                                <p class="text-red-600">Due Date {{ $ticket->due_date }}</p>
                            @else
                                <p class="text-green-600">Due Date {{ $ticket->due_date }}</p>
                            @endif
                            <p>{{ $ticket->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <p class="text-white">You don't have any support tickets yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
