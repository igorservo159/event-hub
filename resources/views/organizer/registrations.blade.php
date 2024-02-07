<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inscrições do Evento') }}
        </h2>
    </x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            @if(session('error') || session('success'))
                <div class="mb-4">
                    <div class="bg-{{ session('error') ? 'red' : 'green' }}-500 border border-{{ session('error') ? 'red' : 'green' }}-400 text-{{ session('error') ? 'red' : 'green' }}-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">{{ session('error') ? 'Erro!' : 'Sucesso!' }}</strong>
                        <span class="block sm:inline">{{ session('error') ?: session('success') }}</span>
                    </div>
                </div>
            @endif
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold mb-4">{{ $event->title }}</h3>
                <div class="mb-4 flex justify-between">
                    <div>
                        <p>{{ $event->registrations->where('status', '!=', 'cancelada')->count() }}/{{ $event->capacity }} vagas preenchidas</p>
                        <p>{{ $paidCount }} pessoas pagaram</p>
                        <p>{{ $pendingCount }} pessoas com pagamento pendente</p>
                        <p>{{ $processingCount }} pessoas com pagamento em análise</p>
                        <p>{{ $waitingCount }} pessoas esperando por reembolso</p>
                        <p>{{ $cancelCount }} inscrições canceladas</p>
                    </div>
                    <div>
                        <a class="hover:underline" href="{{ route('refunds.listRefunds', $event) }}">Checar Pedidos de Reembolsos</a>
                    </div>
                </div>
                <div class="mb-3">
                    <form action="{{ route('registrations.listRegisters', $event) }}" method="get">
                        <div class="mb-4">
                            <label for="user_name" class="block text-gray-600 text-sm font-medium mb-2">Name:</label>
                            <input type="text" name="user_name" id="user_name" class="w-full border-gray-300 rounded-md p-2">
                        </div>
                        <div class="mb-4">
                            <label for="status_filter" class="block text-gray-600 text-sm font-medium mb-2">Status do Pagamento:</label>
                            <select name="status_filter" id="status_filter" class="w-full border-gray-300 rounded-md p-2">
                                <option value="" selected>Todos</option>
                                <option value="pendente" @if(request('status_filter') == 'pendente') selected @endif>Pendente</option>
                                <option value="pago" @if(request('status_filter') == 'pago') selected @endif>Pago</option>
                                <option value="processando pagamento" @if(request('status_filter') == 'processando pagamento') selected @endif>Processando Pagamento</option>
                                <option value="esperando por reembolso" @if(request('status_filter') == 'esperando por reembolso') selected @endif>Esperando por reembolso</option>
                                <option value="cancelada" @if(request('status_filter') == 'cancelada') selected @endif>Cancelada</option>
                            </select>
                        </div>
                        <div class="flex justify-between">
                            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Filtrar</button>
                            <a href="{{ route('registrations.listRegisters', $event) }}" class="text-gray-500 hover:underline">Limpar Filtro</a>
                        </div>
                    </form>
                </div>
            </div>
        
            <div class="mt-2 p-6 bg-white border-b border-gray-200">
                <h2 class="text-lg font-semibold mb-4">Lista de Inscrições</h2>
                @if($registrations->isEmpty())
                    <p>Nenhuma inscrição encontrada.</p>
                @else
                    <ul>
                        @foreach($registrations as $registration)
                            <li class="mb-4 p-4 border border-gray-300 rounded-md">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p>
                                            {{ $registration->user->name }} - {{ $registration->created_at->format('d/m/Y') }}
                                        </p>
                                        <p>
                                            Status: {{ $registration->status }}
                                        </p>
                                    </div>
                                    @if ($registration->status === 'processando pagamento')
                                        <form action="{{ route('payments.approvePayment', $registration) }}" method="post">
                                            @csrf
                                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Aprovar Pagamento</button>
                                        </form>
                                        <form action="{{ route('payments.denyPayment', $registration) }}" method="post">
                                            @csrf
                                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md">Negar Pagamento</button>
                                        </form>
                                    @endif
                                    @if ($registration->status === 'esperando por reembolso')
                                        @php
                                            $refund = $registration->payments->where('status', 'finalizado')->first()->refunds->where('decisao', 'pendente')->first();
                                        @endphp
                                        @if ($refund && $refund->decisao === 'pendente')
                                            <form action="{{ route('refunds.show', $refund) }}" method="get">
                                                @csrf
                                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md">Ver Pedido de Reembolso</button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
