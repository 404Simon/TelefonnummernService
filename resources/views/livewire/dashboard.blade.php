<div class="p-4 space-y-4 mx-4 md:mx-30 my-10">
    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Telefonnummer</label>
    <x-input class="text-black" label="Phone number" hint="Insert your phone number" wire:model.live="phone"
        wire:keyup.enter="updatedPhone" />

    @isset($phoneNumber)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- left column: raw data --}}
            <div class="space-y-1">
                <div class="text-gray-700 dark:text-gray-200">
                    Original Input: <span class="font-semibold">{{ $phoneNumber['phoneNumber'] }}</span>
                </div>
                <div class="text-gray-700 dark:text-gray-200">
                    Country Code: <span class="font-semibold">{{ $phoneNumber['countryCode'] }}</span>
                </div>
                <div class="text-gray-700 dark:text-gray-200">
                    NDC (Vorwahl ohne 0): <span class="font-semibold">{{ $phoneNumber['ndc'] }}</span>
                </div>
                <div class="text-gray-700 dark:text-gray-200">
                    Region: <span class="font-semibold">{{ $phoneNumber['region'] ?? '–' }}</span>
                </div>
                <div class="text-gray-700 dark:text-gray-200">
                    Mobile Provider: <span class="font-semibold">{{ $phoneNumber['mobileProvider'] ?? '–' }}</span>
                </div>
                <div class="text-gray-700 dark:text-gray-200">
                    Subscriber Number: <span class="font-semibold">{{ $phoneNumber['subscriberNumber'] }}</span>
                </div>
                <div class="text-gray-700 dark:text-gray-200">
                    Direct Dialing Code: <span class="font-semibold">{{ $phoneNumber['directDialingCode'] ?? '–' }}</span>
                </div>
                <div class="text-gray-700 dark:text-gray-200">
                    ISO‑3166 α‑2: <span class="font-semibold">{{ $phoneNumber['iso3166alpha2'] }}</span>
                </div>
                <div class="text-gray-700 dark:text-gray-200">
                    Flag: <span class="font-semibold">{{ $phoneNumber['flag'] }}</span>
                </div>
                <div class="text-gray-700 dark:text-gray-200">
                    Type: <span class="font-semibold">{{ $phoneNumber['type'] ?? '–' }}</span>
                </div>
            </div>

            {{-- right column: formatted display --}}
            <div class="space-y-1">
                <div class="text-sm text-gray-600 dark:text-gray-400">Formatiert (DIN 5008):</div>
                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $phoneNumber['formattedPhone'] ?? '–' }}</div>
            </div>

        </div>
    @endisset
</div>
