<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pagamento') }}
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

                    <form method="POST" action="{{ route('payments.store', $registration) }}">
                        @csrf
                        <div class="mb-4">
                            <label for="value" class="block text-gray-700 text-sm font-bold mb-2">Valor</label>
                            <p class="form-static">{{ $eventPrice }}</p>
                            <input type="hidden" name="value" value="{{ $eventPrice }}">
                        </div>

                        <div class="mb-4">
                            <label for="method" class="block text-gray-700 text-sm font-bold mb-2">Forma de Pagamento</label>
                            <select name="method" id="method" class="form-select w-full" required>
                                <option value="pix">Pix</option>
                                <option value="credito">Cartão de Crédito</option>
                                <option value="debito">Cartão de Débito</option>
                                <option value="boleto">Boleto</option>
                            </select>
                        </div>

                        <input type="hidden" name="registration_id" value="{{ $registration->id }}">

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Realizar Pagamento</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
