<!-- resources/views/events/edit.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Evento') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="bg-white rounded-lg overflow-hidden shadow-md">
            <div class="p-6">
                <form method="POST" action="{{ route('events.update', $event) }}">
                    @csrf
                    @method('put')

                    <div class="mb-4">
                        <label for="title" class="block text-gray-600 text-sm font-medium mb-2">Título:</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" class="w-full border-gray-300 rounded-md p-2" maxlength="255">
                        <span class="text-gray-500 text-sm">{{ strlen(old('title', $event->title)) }}/255 caracteres</span>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-gray-600 text-sm font-medium mb-2">Descrição:</label>
                        <textarea name="description" id="description" class="w-full border-gray-300 rounded-md p-2">{{ old('description', $event->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="data" class="block text-gray-600 text-sm font-medium mb-2">Data:</label>
                        <input type="datetime-local" name="data" id="data" value="{{ old('data', $event->data->format('Y-m-d\TH:i')) }}" class="w-full border-gray-300 rounded-md p-2">
                    </div>

                    <div class="mb-4">
                        <label for="location" class="block text-gray-600 text-sm font-medium mb-2">Localização:</label>
                        <input type="text" name="location" id="location" value="{{ old('location', $event->location) }}" class="w-full border-gray-300 rounded-md p-2" maxlength="255">
                        <span class="text-gray-500 text-sm">{{ strlen(old('location', $event->location)) }}/255 caracteres</span>
                    </div>

                    <div class="mb-4">
                        <label for="capacity" class="block text-gray-600 text-sm font-medium mb-2">Capacidade:</label>
                        <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $event->capacity) }}" class="w-full border-gray-300 rounded-md p-2">
                    </div>
                    
                    <div class="mb-4">
                        <label for="price" class="block text-gray-600 text-sm font-medium mb-2">Preço:</label>
                        @if($registrationsWithPayment)
                            <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $event->price) }}" class="w-full border-gray-300 rounded-md p-2" readonly>
                            <span class="text-gray-500 text-sm">Por haver inscritos pagos/pagando, não é permitido mudar o preço</span>
                        @else
                            <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $event->price) }}" class="w-full border-gray-300 rounded-md p-2">
                        @endif
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
