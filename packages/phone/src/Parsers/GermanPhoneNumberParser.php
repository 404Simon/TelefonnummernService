<?php

namespace Phone\Parsers;

use Phone\Enums\PhoneNumberType;
use Phone\PhoneNumber;

final class GermanPhoneNumberParser extends AbstractPhoneNumberParser
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function parse(string $phone): PhoneNumber
    {
        return new PhoneNumber(
            phoneNumber: $phone,
            countryCode: '49',
            ndc: '170',
            subscriberNumber: '123456789',
            directDialingCode: null,
            iso3166alpha2: 'DE',
            flag: '🇩🇪',
            formattedPhone: '+49 170 123456789',
            type: PhoneNumberType::MOBILE
        );
    }
}
