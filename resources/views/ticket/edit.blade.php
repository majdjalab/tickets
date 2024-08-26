<x-app-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900" id="app">
        <h1 class="text-white text-lg font-bold">Update support ticket</h1>
        <div class="w-full sm:max-w-xl mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <form method="POST" action="{{ route('ticket.update', $ticket->id) }}" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <div class="mt-4">
                    <x-input-label for="title" :value="__('Title')" />
                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" autofocus
                                  value="{{ $ticket->title }}" />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>


                @if (auth()->user()->isAdmin)
                    <div class="mt-4">
                        <x-input-label for="users" :value="__('Change to another user')" />
                            <x-select-list name="user_id" id="users" selected="{{ $ticket->user->id }}">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}"  {{ $user->id == $ticket->user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </x-select-list>
                    </div>
                @else
                    <p class="text-white">Status: {{ $ticket->user->name }} </p>
                @endif

                <div class="mt-4" >


                    <vue-calender ticket-id="{{$ticket->id}}"></vue-calender>

                    <x-input-label for="description" :value="__('Description')" />
                    <x-textarea placeholder="Add description" name="description" id="description"
                                value="{{ $ticket->description }}" />
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="categories" :value="__('Categories')" />
                    <div id="app">
                        <category-list :categories="{{ $categories }}" :selected='@json(old("categories", []))'></category-list>
                    </div>
                    <x-input-error :messages="$errors->get('categories')" class="mt-2" />
                </div>

                <div class="mt-4">
                    @if ($ticket->attachment)
                        <a href="{{ '/storage/' . $ticket->attachment }}" class="text-white" target="_blank">See
                            Attachment</a>
                    @endif
                    <x-input-label for="attachment" :value="__('Attachment (if any)')" />
                    <x-file-input name="attachment" id="attachment" />
                    <x-input-error :messages="$errors->get('attachment')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button class="ml-3">
                        Update
                    </x-primary-button>
                </div>

            </form>
        </div>

    </div>

</x-app-layout>
