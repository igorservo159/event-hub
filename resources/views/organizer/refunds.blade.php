<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pedidos de reembolsos do evento: ') }} {{$event->title}}
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
            <form action="{{ route('refunds.listRefunds', $event) }}" method="get" class="bg-white p-6 rounded-lg shadow-md">
                <div class="mb-4">
                    <label for="name_filter" class="block text-gray-600 text-sm font-medium mb-2">Nome do usuário:</label>
                    <input type="text" name="name_filter" id="name_filter" class="w-full border-gray-300 rounded-md p-2">
                </div>

                <div class="flex justify-between">
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Filtrar</button>
                    <a href="{{ route('refunds.listRefunds', $event) }}" class="text-gray-500 hover:underline">Limpar Filtro</a>
                    <a href="{{ route('registrations.listRegisters', $event) }}" class="text-gray-500 hover:underline">Voltar</a>
                </div>
            </form>
        </div>

        @forelse($refunds as $refund)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="flex space-x-2">
                        <div class="flex-1">
                            <div class="flex justify-between px-6 pt-6 pb-3">
                                <h2 class="break-words max-w-lg text-xl font-semibold mb-2">{{$refund->user->name}}</h2>
                                <div>
                                    <p class="text-md text-gray-800">{{ __('Decisão: :decisao', ['decisao' => $refund->decisao]) }}</p>
                                </div>
                            </div>
                            <div>
                                <p class="px-6 mb-2 text-md text-gray-800">{{ __('Motivo: :reason', ['reason' => $refund->reason]) }}</p>
                            </div>
                                <p class="px-6 mb-2 text-md text-gray-500">{{ __('Valor: $:value', ['value' => number_format($refund->value, 2)]) }}</p>
                            <div>
                                <p class="px-6 mb-2 text-gray-600 break-words">{{ __('Explicação: :explanation', ['explanation' => $refund->explanation]) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <p class="text-center text-gray-500">{{ __('Você ainda não fez nenhum pagamento.') }}</p>
        @endforelse
    </div>
</x-app-layout>
