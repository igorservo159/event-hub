<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Encontre seu evento') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        @if(session('error') || session('success'))
            <div class="mb-4">
                <div class="bg-{{ session('error') ? 'red' : 'green' }}-500 border border-{{ session('error') ? 'red' : 'green' }}-400 text-{{ session('error') ? 'red' : 'green' }}-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">{{ session('error') ? 'Erro!' : 'Sucesso!' }}</strong>
                    <span class="block sm:inline">{{ session('error') ?: session('success') }}</span>
                </div>
            </div>
        @endif
        <div class="mb-6">
            <form action="{{ route('events.index') }}" method="get" class="bg-white p-6 rounded-lg shadow-md">
                <div class="mb-4">
                    <label for="title_filter" class="block text-gray-600 text-sm font-medium mb-2">Título:</label>
                    <input type="text" name="title_filter" id="title_filter" class="w-full border-gray-300 rounded-md p-2">
                </div>
            
                <div class="mb-4">
                    <label for="date_filter" class="block text-gray-600 text-sm font-medium mb-2">Data:</label>
                    <input type="date" name="date_filter" id="date_filter" class="w-full border-gray-300 rounded-md p-2">
                </div>
            
                <div class="mb-4">
                    <label for="location_filter" class="block text-gray-600 text-sm font-medium mb-2">Localização:</label>
                    <input type="text" name="location_filter" id="location_filter" class="w-full border-gray-300 rounded-md p-2">
                </div>
                
                <div class="flex justify-between">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Filtrar</button>
                    <a href="{{ route('events.index') }}" class="text-gray-500 hover:underline">Limpar Filtro</a>
                </div>
            </form>
        </div>

        @foreach($events as $event)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-2 break-words">{{ $event->title }}</h2>
                    <p class="text-sm text-gray-500">{{ __('Data: :date', ['date' => $event->data->format('d-m-Y H:i')]) }}</p>
                    <p class="text-sm text-gray-500">{{ __('Local: :location', ['location' => $event->location]) }}</p>
                    <p class="text-sm text-gray-500">{{ __('Organizador: :organizer', ['organizer' => $event->owner->name]) }}</p>
                    <a href="{{ route('events.show', $event) }}" class="text-gray-700 hover:underline">Ver detalhes</a>
                </div>
            </div>
        @endforeach

        @if($events->isEmpty())
            <p class="text-center text-gray-500">{{ __('No events available.') }}</p>
        @endif
    </div>
</x-app-layout>
