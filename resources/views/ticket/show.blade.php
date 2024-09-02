<x-app-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <h1 class="text-white text-lg font-bold">{{ $ticket->title }}</h1>

        <div class="w-full sm:max-w-xl mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <div class="text-white flex justify-between py-4">
                <p>{{ $ticket->description }}</p>
                <p>{{ $ticket->created_at->diffForHumans() }}</p>

                <div>
                    <p>Created By: {{ $ticket->user->name }}</p>
                    <p>Categories:
                        @if (count($categoryNames) > 0)
                            @foreach ($categoryNames as $categoryName)
                                {{ $categoryName }}@if (!$loop->last), @endif
                            @endforeach
                        @else
                            No Category
                        @endif
                    </p>
                    <p>Due Date: {{ $ticket->due_date }}</p>
                </div>

                @if ($ticket->attachment)
                    <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank" class="text-blue-500 hover:underline">Attachment</a>
                @endif
            </div>

            <div class="flex justify-between">
                <div class="flex">
                    <a href="{{ route('ticket.edit', $ticket->id) }}">
                        <x-primary-button>Edit</x-primary-button>
                    </a>

                    <form class="ml-3" action="{{ route('ticket.destroy', $ticket->id) }}" method="post">
                        @method('delete')
                        @csrf
                        <x-primary-button>Delete</x-primary-button>
                    </form>
                </div>

                <form action="{{ route('ticket.update', $ticket->id) }}" method="post">
                    @csrf
                    @method('patch')
                    <input type="hidden" name="assigned_at" value="{{ date('Y-m-d') }}" />
                    <x-primary-button>Assign</x-primary-button>
                </form>


                <div class="flex">
                    </div>
                @if (auth()->user()->isAdmin)
                    <div class="flex">
                        <form action="{{ route('ticket.update', $ticket->id) }}" method="post">
                            @csrf
                            @method('patch')
                            <input type="hidden" name="status" value="resolved" />
                            <x-primary-button>Resolve</x-primary-button>
                        </form>

                        <form action="{{ route('ticket.update', $ticket->id) }}" method="post" class="ml-3">
                            @csrf
                            @method('patch')
                            <input type="hidden" name="status" value="rejected" />
                            <x-primary-button class="ml-3">Reject</x-primary-button>
                        </form>
                    </div>
                @else
                    <p class="text-white">Status: {{ $ticket->status }}</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
