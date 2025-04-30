<div class="p-4 bg-white rounded-lg shadow-md space-y-4">
    <label for="phone" class="block text-sm font-medium text-gray-700">Telefonnummer</label>
    <input id="phone" type="text" wire:model.live="phone" wire:keyup.enter="updatedPhone"
        placeholder="z. B. +49 30 1234567 x89"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-opacity-50" />
    @error('phone')
        <div class="text-red-600 text-sm">{{ $message }}</div>
    @enderror

    @if ($formattedPhone)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1">
                <div>Ländervorwahl: <span class="font-semibold">{{ $countryCode }}</span></div>
                <div>Ortsvorwahl: <span class="font-semibold">{{ $areaCode }}</span></div>
                <div>Hauptnummer: <span class="font-semibold">{{ $mainNumber }}</span></div>
                @if ($countryName)
                    <div>Land: <span class="font-semibold">{{ $countryName }} {{ $countryFlag }}</span></div>
                @endif
            </div>
            <div class="space-y-1">
                <div class="text-sm text-gray-600">Formatiert (DIN 5008):</div>
                <div class="text-lg font-semibold">{{ $formattedPhone }}</div>
            </div>
        </div>
    @endif
</div>
