<!-- resources/views/organizer/registrations.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inscrições do Evento') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <h3 class="text-lg font-semibold mb-4">{{ $event->title }}</h3>

        <div class="mb-4">
            <p>{{ $event->registrations->count() }} vagas no total</p>
            <p>{{ $paidCount }} pessoas já pagaram</p>
            <p>{{ $pendingCount }} pessoas com pagamento pendente</p>
            <p>{{ $processingCount }} pessoas com pagamento em análise</p>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-lg font-semibold mb-4">Lista de Inscrições</h2>

                @if($registrations->isEmpty())
                    <p>Nenhuma inscrição encontrada.</p>
                @else
                    <ul>
                        @foreach($registrations as $registration)
                            <li>
                                {{ $registration->user->name }} - {{ $registration->created_at->format('d/m/Y') }} - Status: {{ $registration->status }}
                                @if ($registration->status === 'processando pagamento')
                                    <form action="{{ route('registrations.approvePayment', $registration) }}" method="post">
                                        @csrf
                                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Aprovar Pagamento</button>
                                    </form>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
