<?php

namespace Phone;

use Phone\Enums\PhoneNumberType;

class PhoneNumber
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        // The original phone number input, for example +49170123456789-33
        public readonly string $phoneNumber,
        // The country code, for exaple 49 for Germany
        public readonly string $countryCode,
        // The National Destination Code (NDC), for example 170 for mobile numbers in Germany or 30 for Berlin (Vorwahl ohne 0)
        public readonly string $ndc,
        // The subscriber number, for example 123456789 (Hauptwahl)
        public readonly string $subscriberNumber,
        // The direct dialing code, for example 33 for Germany (Durchwahl)
        public readonly ?string $directDialingCode,
        // The country abbreviation, for example DE for Germany (Länderkürzel)
        public readonly string $iso3166alpha2,
        // The flag emoji, for example 🇩🇪 for Germany
        public readonly string $flag,
        // The formatted phone number, for example +49 170 123456789
        public readonly string $formattedPhone,
        // The type of the phone number, for example mobile, landline, etc.
        public readonly PhoneNumberType $type
    ) {}
}
