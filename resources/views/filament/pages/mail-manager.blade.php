<x-filament-panels::page>
    <div class="flex justify-end mb-4">
        <x-filament::button wire:click="goToSendEmail">
            ✉️ Nouveau message
        </x-filament::button>
    </div>

    <div class="space-y-4">
        @forelse ($messages as $mail)
            <div class="border p-4 rounded bg-white shadow">
                <div class="text-sm text-gray-500">{{ $mail['created'] ?? '—' }}</div>
                <div class="font-bold text-lg text-indigo-700">
                    {{ $mail['from'][0]['address'] ?? 'Inconnu' }}
                </div>
                <div class="text-md">{{ $mail['subject'] ?? '(sans sujet)' }}</div>
            </div>
        @empty
            <p class="text-gray-500">Aucun message reçu pour le moment.</p>
        @endforelse
    </div>
</x-filament-panels::page>
