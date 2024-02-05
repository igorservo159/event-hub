<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pedido de Reembolso') }}
        </h2>
    </x-slot>

    <div class="container mx-auto my-8">
        <div class="flex justify-center">
            <div class="w-full max-w-lg">
                <div class="bg-white rounded-md shadow-md p-6">
                    @if(session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('refunds.store', $registration) }}">
                        @csrf
                        <div class="mb-4">
                            <label for="value" class="block text-gray-700 text-sm font-bold mb-2">Valor</label>
                            <p class="form-static">{{ $registration->event->price }}</p>
                            <input type="hidden" name="value" value="{{ $registration->event->price }}">
                        </div>

                        <div class="mb-4">
                            <label for="reason" class="block text-gray-700 text-sm font-bold mb-2">Motivo do Reembolso</label>
                            <select name="reason" id="reason" class="form-select w-full" required>
                                <option value="" disabled selected>Selecione um motivo</option>
                                <option value="evento_errado">Me inscrevi no evento errado</option>
                                <option value="imprevisto">Imprevisto</option>
                                <option value="nao_gostou">Não gostou do evento</option>
                                <option value="outro">Outro</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="explanation" class="block text-gray-700 text-sm font-bold mb-2">Explique melhor</label>
                            <textarea name="explanation" id="explanation" class="form-input w-full" required></textarea>
                        </div>

                        <input type="hidden" name="payment_id" value="{{ $payment->id }}">

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Enviar Pedido de Reembolso</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
