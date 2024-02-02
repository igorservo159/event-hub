<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crie seu evento') }}
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

                    <form method="POST" action="{{ route('events.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Título</label>
                            <input type="text" name="title" id="title" class="form-input w-full" maxlength="255" required>
                            <span class="text-gray-500 text-sm">255 caracteres</span>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Descrição</label>
                            <textarea name="description" id="description" class="form-input w-full" required></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="data" class="block text-gray-700 text-sm font-bold mb-2">Data</label>
                            <input type="datetime-local" name="data" id="data" class="form-input w-full" required>
                        </div>

                        <div class="mb-4">
                            <label for="location" class="block text-gray-700 text-sm font-bold mb-2">Localização</label>
                            <input type="text" name="location" id="location" class="form-input w-full" maxlength="255" required>
                            <span class="text-gray-500 text-sm">255 caracteres</span>
                        </div>

                        <div class="mb-4">
                            <label for="capacity" class="block text-gray-700 text-sm font-bold mb-2">Capacidade</label>
                            <input type="number" name="capacity" id="capacity" class="form-input w-full" required>
                        </div>

                        <div class="mb-4">
                            <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Preço</label>
                            <input type="number" step="0.01" name="price" id="price" class="form-input w-full" required>
                        </div>

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Criar Evento</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
