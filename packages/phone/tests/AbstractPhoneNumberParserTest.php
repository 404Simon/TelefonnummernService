<?php

namespace Phone\Feature;

use Phone\Exceptions\CountryCodeParserException;
use Phone\Parsers\AbstractPhoneNumberParser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AbstractPhoneNumberParserTest extends TestCase
{
    /**
     * A test if the country code is parsed correctly.
     */
    #[Test]
    #[DataProvider('validPhoneNumberProvider')]
    public function get_country_code_should_return_country_code(string $phoneNumber, string $countryCode, string $ndc, string $subscriberNumber, ?string $directDialingCode, string $iso3166alpha2, string $flag, string $formattedPhone): void
    {
        $result = AbstractPhoneNumberParser::getCountryCode($phoneNumber);
        $this->assertEquals($countryCode, $result);
    }

    /**
     * A test if invalid country code is detected.
     */
    #[Test]
    #[DataProvider('invalidPhoneNumberProvider')]
    public function get_country_code_should_return_error(string $phoneNumber, ?string $countryCode, string $ndc, string $subscriberNumber, ?string $directDialingCode, string $iso3166alpha2, string $flag, string $formattedPhone): void
    {
        $this->assertThrows(
            function () use ($phoneNumber) {
                AbstractPhoneNumberParser::getCountryCode($phoneNumber);
            }, CountryCodeParserException::class
        );
    }

    public static function validPhoneNumberProvider()
    {
        return [
            '+49170123456789' => [
                'phoneNumber' => '+49170123456789',
                'countryCode' => '49',
                'ndc' => '170',
                'subscriberNumber' => '123456789',
                'directDialingCode' => null,
                'iso3166alpha2' => 'DE',
                'flag' => '🇩🇪',
                'formattedPhone' => '+49 170 123456789',
            ],
            '+49170123456789-33' => [
                'phoneNumber' => '+49170123456789-33',
                'countryCode' => '49',
                'ndc' => '170',
                'subscriberNumber' => '123456789',
                'directDialingCode' => '33',
                'iso3166alpha2' => 'DE',
                'flag' => '🇩🇪',
                'formattedPhone' => '+49 170 123456789-33',
            ],
            '0170123456789' => [
                'phoneNumber' => '0170123456789',
                'countryCode' => '49',
                'ndc' => '170',
                'subscriberNumber' => '123456789',
                'directDialingCode' => null,
                'iso3166alpha2' => 'DE',
                'flag' => '🇩🇪',
                'formattedPhone' => '+49 170 123456789',
            ],
        ];
    }

    public static function invalidPhoneNumberProvider()
    {
        return [
            '+85170123456789' => [
                'phoneNumber' => '+85170123456789',
                'countryCode' => null,
                'ndc' => '170',
                'subscriberNumber' => '123456789',
                'directDialingCode' => null,
                'iso3166alpha2' => 'DE',
                'flag' => '🇩🇪',
                'formattedPhone' => '+49 170 123456789',
            ],
            '+67170123456789-33' => [
                'phoneNumber' => '+67170123456789-33',
                'countryCode' => null,
                'ndc' => '170',
                'subscriberNumber' => '123456789',
                'directDialingCode' => null,
                'iso3166alpha2' => 'DE',
                'flag' => '🇩🇪',
                'formattedPhone' => '+49 170 123456789-33',
            ],
        ];
    }
}
