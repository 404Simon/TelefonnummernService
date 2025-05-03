<?php

namespace Phone\Parsers;

use Phone\Enums\PhoneNumberType;

/**
 * Länderspezifischer Parser für deutsche Telefonnummern.
 *
 * Enthält Konfiguration für Vorwahlen, Längen, Typbereiche sowie optionale
 * regionale und Anbieter-Zuordnungen.
 */
class GermanPhoneNumberParser extends AbstractPhoneNumberParser
{
    protected string $countryCode = '49';

    protected string $internationalPrefix = '00';

    protected string $nationalPrefix = '0';

    /**
     * Typische NDC-Längen in Deutschland: 2-5 Ziffern
     */
    protected array $ndcLengths = [2, 3, 4, 5];

    /**
     * Teilnehmernummern können typischerweise zwischen 3 und 11 Ziffern haben
     */
    protected array $subscriberLengths = [3, 4, 5, 6, 7, 8, 9, 10, 11];

    /**
     * Bereiche für verschiedene Nummerntypen basierend auf der NDC
     */
    protected array $ndcTypeRanges = [
        PhoneNumberType::LANDLINE->value => [10 => 5999], // Ortsnetzbereiche
        PhoneNumberType::MOBILE->value => [150 => 179], // Mobilfunk
        PhoneNumberType::SERVICE->value => [180 => 189], // Servicenummern (z. B. 0180)
    ];

    /**
     * Feste Region-Zuordnungen für bekannte Festnetzbereiche
     */
    protected ?array $ndcFixedRegions = [
        '30' => 'Berlin',
        '40' => 'Hamburg',
        '89' => 'München',
        '69' => 'Frankfurt am Main',
        '228' => 'Bonn',
        '231' => 'Dortmund',
        // bitte erweitere mich
    ];

    /**
     * Anbieter-Zuordnung für Mobilfunknummern
     */
    protected ?array $ndcMobileProviders = [
        '151' => 'Telekom',
        '160' => 'Telekom',
        '170' => 'Vodafone',
        '176' => 'Telefonica',
        // weiter hier
    ];

    /**
     * Gibt den Standard-ISO-Ländercode für Deutschland zurück.
     */
    protected function getRegion(): string
    {
        return 'DE';
    }
}
