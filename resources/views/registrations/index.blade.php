<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Suas Inscrições') }}
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
            <form action="{{ route('registrations.index') }}" method="get" class="bg-white p-6 rounded-lg shadow-md">
                <div class="mb-4">
                    <label for="title_filter" class="block text-gray-600 text-sm font-medium mb-2">Título do Evento:</label>
                    <input type="text" name="title_filter" id="title_filter" class="w-full border-gray-300 rounded-md p-2" value="{{$titleFilter}}">
                </div>

                <div class="flex justify-between">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Filtrar</button>
                    <a href="{{ route('registrations.index') }}" class="text-gray-500 hover:underline">Limpar Filtro</a>
                </div>
            </form>
        </div>

        @foreach($registrations as $registration)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="flex space-x-2">
                    <div class="flex-1">
                        <div class="flex justify-between px-6 pt-6 pb-3">                
                            <h2 class="break-words max-w-lg text-xl font-semibold mb-2">
                                @if($registration->status === "cancelada")
                                    <span class="text-red-500">{{ $registration->event->title }} (Você cancelou sua inscrição)</span>
                                @else
                                    {{ $registration->event->title }}
                                @endif
                            </h2>
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
                                        @if($registration->status == "pendente" && $registration->status !== "cancelada")
                                            <x-dropdown-link :href="route('payments.create', $registration)">
                                                {{ __('Realizar pagamento') }}
                                            </x-dropdown-link>
                                        @endif
                                        @if($registration->status != "esperando por reembolso" && $registration->status !== "cancelada")
                                            <form method="POST" action="{{ route('registrations.destroy', $registration) }}">
                                                @csrf
                                                @method('delete')
                                                <x-dropdown-link :href="route('registrations.destroy', $registration)" onclick="event.preventDefault(); this.closest('form').submit();">
                                                    @if($registration->status == "pago"){{ __('Cancelar inscrição e pedir reembolso') }}
                                                    @else{{ __('Cancelar inscrição') }}@endif
                                                </x-dropdown-link>
                                            </form>
                                        @endif
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </div>
                        <div class="px-6 grid grid-cols-2 mb-2">
                            <div>
                                <p class="text-md text-gray-500">{{ __('Data: :date', ['date' => $registration->event->data->format('d-m-Y H:i')]) }}</p>
                                <p class="text-md text-gray-500">{{ __('Lotação: :location', ['location' => $registration->event->location]) }}</p>
                                <p class="text-md text-gray-500">{{ __('Capacidade: :capacity', ['capacity' => $registration->event->capacity]) }}</p>
                                <p class="text-md text-gray-500">{{ __('Preço: $:price', ['price' => number_format($registration->event->price, 2)]) }}</p>
                                <p class="text-md text-gray-500">{{ __('Organizador: :organizer', ['organizer' => $registration->event->owner->name]) }}</p>
                                <p class="text-md text-gray-800">{{ __('Pagamento: :status', ['status' => $registration->status]) }}</p>
                            </div>
                            <p class="flex max-w-xs text-gray-600 break-words">{{ __('Descrição: :description', ['description' => $registration->event->description]) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach


        @if($registrations->isEmpty())
            <p class="text-center text-gray-500">{{ __('Você ainda não se inscreveu em nenhum evento.') }}</p>
        @endif
    </div>
</x-app-layout>
