<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
            @if(session('error') || session('success'))
                <div class="mb-4">
                    <div class="bg-{{ session('error') ? 'red' : 'green' }}-100 border border-{{ session('error') ? 'red' : 'green' }}-400 text-{{ session('error') ? 'red' : 'green' }}-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">{{ session('error') ? 'Erro!' : 'Sucesso!' }}</strong>
                        <span class="block sm:inline">{{ session('error') ?: session('success') }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
