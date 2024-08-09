<x-app-layout>
    <x-guest-layout>
        <form method="POST">
            <div class="w-full">
                <x-input-label for="Title" :value="__('title')" />
                <x-text-input class="block mt-1 w-full" />
            </div>

            <div class="mt-4">
                <x-input-label for="Description" :value="__('description')" />
                <x-text-input class="block mt-1 w-full" />
            </div>
            <div>
                <x-input-label for="name" value="Avatar" />
                <x-text-input id="Attachment" name="attachment" type="file" class="mt-1 block w-full" : />
                <x-input-error class="mt-2" :messages="$errors->get('attachment')" />
            </div>
            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="ms-3">
                    {{ __('Create') }}
                </x-primary-button>
            </div>
        </form>
    </x-guest-layout>
</x-app-layout>
