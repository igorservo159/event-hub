<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes do Evento') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="bg-white rounded-lg overflow-hidden shadow-md">
            <div class="p-6">
                <div class="px-3">
                    <h2 class="break-words text-xl font-semibold mb-2">{{ $event->title }}</h2>
                    <div class="grid grid-cols-2">
                        <div>
                            <p class="text-sm text-gray-500">{{ __('Data: :date', ['date' => $event->data->format('d-m-Y H:i')]) }}</p>
                            <p class="text-sm text-gray-500">{{ __('Lotação: :location', ['location' => $event->location]) }}</p>
                            <p class="text-sm text-gray-500">{{ __('Capacidade: :capacity', ['capacity' => $event->capacity]) }}</p>
                            <p class="text-sm text-gray-500">{{ __('Preço: $:price', ['price' => number_format($event->price, 2)]) }}</p>
                            <p class="text-sm text-gray-500">{{ __('Organizador: :organizer', ['organizer' => $event->owner->name]) }}</p>
                        </div>
                        <p class="flex justify-center max-w-xs text-gray-600 break-words">{{ __('Descrição: :description', ['description' => $event->description]) }}</p>
                    </div>
                </div>
                <div class="flex justify-between mx-3">

                    @if(Auth::user()->hasActiveEnrollment($event))
                        <p class="text-green-500 mt-4">{{ __('Você já está inscrito neste evento.') }}</p>
                    @else
                        <form action="{{ route('registrations.store', $event) }}" method="post" class="mt-4">
                            @csrf
                            <button type="submit" class="bg-white text-blue-500 hover:underline">Inscrever-se no Evento</button>
                        </form>
                    @endif
                    <a href="{{ route('events.index') }}" class="text-blue-500 hover:underline mt-4">Voltar para a lista de eventos</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
