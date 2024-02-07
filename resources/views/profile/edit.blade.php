<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    @if(session('error') || session('success'))
        <div class="mb-4">
            <div class="bg-{{ session('error') ? 'red' : 'green' }}-500 border border-{{ session('error') ? 'red' : 'green' }}-400 text-{{ session('error') ? 'red' : 'green' }}-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">{{ session('error') ? 'Erro!' : 'Sucesso!' }}</strong>
                <span class="block sm:inline">{{ session('error') ?: session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
