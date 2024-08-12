<x-app-layout>
    <x-guest-layout>
        <form method="POST" action="{{ route('ticket.store') }}" enctype="multipart/form-data">
@csrf
            <div class="w-full">
                <x-input-label for="Title" :value="__('title')" />
                <x-text-input class="block mt-1 w-full" placeholder="Title" name="title" autofocus/>
                <x-input-error class="mt-2" :messages="$errors->get('title')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="description" :value="__('Description')" />
                <x-textarea placeholder="Add description" name="description" id="description" value="" />
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="attachment" :value="__('Attachment (if any)')"/>
                <x-file-input id="attachment" name="attachment" class="mt-1 block w-full" : />
                <x-input-error class="mt-2" :messages="$errors->get('attachment')" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ms-3">
                  Create
                </x-primary-button>
            </div>

        </form>
    </x-guest-layout>
</x-app-layout>
