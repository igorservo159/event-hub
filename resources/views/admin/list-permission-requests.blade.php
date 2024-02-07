<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Usuários Pendentes') }}
        </h2>
    </x-slot>
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            @if(session('error') || session('success'))
                <div class="mb-4">
                    <div class="bg-{{ session('error') ? 'red' : 'green' }}-100 border border-{{ session('error') ? 'red' : 'green' }}-400 text-{{ session('error') ? 'red' : 'green' }}-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">{{ session('error') ? 'Erro!' : 'Sucesso!' }}</strong>
                        <span class="block sm:inline">{{ session('error') ?: session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="mt-2 p-6 bg-white border-b border-gray-200">
                <h2 class="text-lg font-semibold mb-4">Lista de Pedidos de Permissão Pendentes</h2>
                @if($permissionRequests->isEmpty())
                    <p>Nenhum pedido de permissão pendente encontrado.</p>
                @else
                    <ul>
                        @foreach($permissionRequests as $request)
                            <li class="mb-4 p-4 border border-gray-300 rounded-md">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p>
                                            ID do Pedido: {{ $request->id }}
                                        </p>
                                        <p>
                                            Usuário: {{ $request->user->name }} | ID: {{ $request->user_id }}
                                        </p>
                                        <p>
                                            Tipo Solicitado: {{ $request->requested_type }}
                                        </p>
                                    </div>
                                    <div>
                                        <form action="{{ route('permissionRequests.approve', $request) }}" method="post">
                                            @csrf
                                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md mt-2">Aprovar</button>
                                        </form>
                                        <form action="{{ route('permissionRequests.deny', $request) }}" method="post">
                                            @csrf
                                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md mt-2">Negar</button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
