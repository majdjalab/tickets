<x-app-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900" id="app">
        <h1 class="text-white text-lg font-bold">Update Support Ticket</h1>

        <div class="w-full sm:max-w-xl mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <form method="POST" action="{{ route('ticket.update', $ticket->id) }}" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <div class="mt-4">
                    <x-input-label for="title" :value="__('Title')" />
                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" autofocus value="{{ old('title', $ticket->title) }}" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                @if (auth()->user()->isAdmin)
                    <div class="mt-4">
                        <x-input-label for="user_id" :value="__('Assign to User')" />
                        <x-select-list name="user_id" id="user_id" class="block mt-1 w-full">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $user->id == $ticket->user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </x-select-list>
                        <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                    </div>
                @else
                    <p class="text-white mt-4">Assigned to: {{ $ticket->user->name }}</p>
                @endif

                <div class="mt-4">
                    <x-input-label for="description" :value="__('Description')" />
                    <x-textarea id="description" name="description" placeholder="Add description">{{ old('description', $ticket->description) }}</x-textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="category_id" :value="__('Category')" />
                    <x-select-list id="category_id" name="category_id" class="block mt-1 w-full">
                        <option value="">Select a Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $category->id == $ticket->category_id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </x-select-list>
                </div>

                <div class="mt-4">
                    @if ($ticket->attachment)
                        <a href="{{ asset('storage/' . $ticket->attachment) }}" class="text-blue-500 hover:underline" target="_blank">View Attachment</a>
                    @endif

                    <x-input-label for="attachment" :value="__('Attachment (optional)')" />
                    <x-file-input name="attachment" id="attachment" />
                    <x-input-error :messages="$errors->get('attachment')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button>
                        Update
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
