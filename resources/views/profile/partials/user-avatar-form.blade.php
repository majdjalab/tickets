<section>
    <header>

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            User Avatar
        </h2>

        <!-- when the user have alredy registerd using github then get the avatar from his github -->
        @if (filter_var($user->avatar, FILTER_VALIDATE_URL))
            <img src="{{ $user->avatar }}" width="100" height="100" class="rounded-full"/>
        <!-- when the user dont have an avatar then get the avatar from the link -->
        @elseif (!$user->avatar)
            <img src="https://cdn-icons-png.flaticon.com/128/9131/9131590.png" width="100" height="100" class="rounded-full">
        @else
            <!-- when the user have already set his avatar then get the avatar from the storage -->
            <img src="{{ asset('storage/' . $user->avatar) }}" width="100" height="100" class="rounded-full"/>
        @endif

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Add or Update User Avatar
        </p>
    </header>

    @if (session('message'))
        <div class="text-red-500">
            {{ session('message') }}
        </div>
    @endif



    <form method="post" action="{{route('profile.avatar')}}" enctype="multipart/form-data">
        @method('patch')
        @csrf
        <div>
            <x-input-label for="name" value="Avatar" />
            <x-text-input id="avatar" name="avatar" type="file" class="mt-1 block w-full" :value="old('avatar', $user->name)" required autofocus autocomplete="avatar" />
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>





        <div class="flex items-center gap-4 mt-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

        </div>
    </form>
</section>
