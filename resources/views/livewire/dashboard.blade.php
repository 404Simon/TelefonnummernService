<div class="p-4 bg-white dark:bg-gray-800 rounded-lg shadow-md space-y-4 mx-4 md:mx-30 my-10">
    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Telefonnummer</label>
        <x-input label="Phone number" hint="Insert your phone number" wire:model.live="phone" wire:keyup.enter="updatedPhone"/>
    
    @error('phone')
        <div class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</div>
    @enderror

    @if ($formattedPhone)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1">
                <div class="text-gray-700 dark:text-gray-200">Ländervorwahl: <span class="font-semibold">{{ $countryCode }}</span></div>
                <div class="text-gray-700 dark:text-gray-200">Ortsvorwahl: <span class="font-semibold">{{ $areaCode }}</span></div>
                <div class="text-gray-700 dark:text-gray-200">Hauptnummer: <span class="font-semibold">{{ $mainNumber }}</span></div>
                @if ($countryName)
                    <div class="text-gray-700 dark:text-gray-200">Land: <span class="font-semibold">{{ $countryName }} {{ $countryFlag }}</span></div>
                @endif
            </div>
            <div class="space-y-1">
                <div class="text-sm text-gray-600 dark:text-gray-400">Formatiert (DIN 5008):</div>
                <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $formattedPhone }}</div>
            </div>
        </div>
    @endif
</div>
