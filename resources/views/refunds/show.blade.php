<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalhes do Pedido de Reembolso') }}
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

    <div class="container mx-auto my-8">
        <div class="flex justify-center">
            <div class="w-full max-w-lg">
                <div class="bg-white rounded-md shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4">Detalhes do Pedido de Reembolso</h3>

                    <p><strong>Valor:</strong> R$ {{ $refund->value }}</p>
                    <p><strong>Razão:</strong> {{ $refund->reason }}</p>
                    <p><strong>Explicação:</strong> {{ $refund->explanation }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($refund->decisao) }}</p>

                    @if ($refund->decisao === 'pendente')
                        <div class="flex justify-between my-2">
                            <div>
                                <form action="{{ route('refunds.approveRefund', $refund) }}" method="post">
                                    @csrf
                                    <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-md">Aprovar Reembolso</button>
                                </form>
                            </div>
                            <div>
                                <form action="{{ route('refunds.denyRefund', $refund) }}" method="post">
                                    @csrf
                                    <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded-md">Negar Reembolso</button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
