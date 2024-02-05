<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Seus Eventos') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="mb-6">
            <form action="{{ route('events.myEvents') }}" method="get" class="bg-white p-6 rounded-lg shadow-md">
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
                    <a href="{{ route('events.myEvents') }}" class="text-gray-500 hover:underline">Limpar Filtro</a>
                </div>
            </form>
        </div>

        @foreach($events as $event)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="flex space-x-2">
                    <div class="flex-1">
                        <div class="flex justify-between px-6 pt-6 pb-3">                
                            <h2 class="break-words max-w-lg text-xl font-semibold mb-2">{{ $event->title }}</h2>
                            <div>
                                <x-dropdown>
                                    <x-slot name="trigger">
                                        <button>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('events.edit', $event)">
                                            {{ __('Editar evento') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('registrations.listRegisters', $event)">
                                            {{ __('Listar inscrições') }}
                                        </x-dropdown-link>
                                        <form method="POST" action="{{ route('events.destroy', $event) }}">
                                            @csrf
                                            @method('delete')
                                            <x-dropdown-link :href="route('events.destroy', $event)" onclick="event.preventDefault(); this.closest('form').submit();">
                                                {{ __('Finalizar Evento') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </div>
                        <div class="px-6 grid grid-cols-2 mb-2">
                            <div>
                                <p class="text-md text-gray-500">{{ __('Data: :date', ['date' => $event->data->format('d-m-Y H:i')]) }}</p>
                                <p class="text-md text-gray-500">{{ __('Lotação: :location', ['location' => $event->location]) }}</p>
                                <p class="text-md text-gray-500">{{ __('Capacidade: :capacity', ['capacity' => $event->capacity]) }}</p>
                                <p class="text-md text-gray-500">{{ __('Preço: $:price', ['price' => number_format($event->price, 2)]) }}</p>
                                <p class="text-md text-gray-500">{{ __('Organizador: :organizer', ['organizer' => $event->owner->name]) }}</p>
                            </div>
                            <p class="flex max-w-xs text-gray-600 break-words">{{ __('Descrição: :description', ['description' => $event->description]) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @if($events->isEmpty())
            <p class="text-center text-gray-500">{{ __('No events available.') }}</p>
        @endif
    </div>
</x-app-layout>
