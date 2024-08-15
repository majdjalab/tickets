<x-app-layout>
    <div class="min-h-screen flex flex-col items-center justify-center sm:justify-center py-6">
        <div class="w-full max-w-xl">
            <div class="flex flex-col  mb-12">
                @if (auth()->user()->isAdmin)
                    <x-input-label for="users" :value="__('Filter By Date Or User')" />
                    <form method="GET" action="{{ route('ticket.index') }}">
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
                            <button type="submit" class="bg-white rounded-lg p-2">Filter</button>
                        </div>
                    </form>

                @else
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
