<x-app-layout>

    <div class="min-h-screen flex flex-col items-center justify-center sm:justify-center py-6" id="app">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <category-form></category-form>
        <category-display :categories="{{ $categories }}" ></category-display>
    </div>
</x-app-layout>
