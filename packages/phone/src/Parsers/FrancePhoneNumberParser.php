<?php

namespace Phone\Parsers;

/**
 * Länderspezifischer Parser für französische Telefonnummern.
 *
 * Enthält Konfiguration für Vorwahlen, Längen, Typbereiche sowie optionale
 * regionale und Anbieter-Zuordnungen.
 */
class FrancePhoneNumberParser extends AbstractPhoneNumberParser
{
    protected string $countryCode = '33';

    protected string $internationalPrefix = '00';

    protected string $nationalPrefix = '0';

    /**
     * Typische NDC-Längen in Deutschland: 2-5 Ziffern
     */
    protected array $ndcLengths = [1];

    /**
     * Teilnehmernummern können typischerweise zwischen 3 und 11 Ziffern haben
     */
    protected array $subscriberLengths = [8];

    /**
     * Bereiche für verschiedene Nummerntypen basierend auf der NDC
     */
    protected array $ndcTypeRanges = [];

    /**
     * Feste Region-Zuordnungen für bekannte Festnetzbereiche
     */
    protected ?array $ndcFixedRegions = [
        '1' => 'Île-de-France',
        '2' => 'Northwest France',
        '3' => 'Northeast France',
        '4' => 'Southeast France',
        '5' => 'Southwest France',
    ];

    /**
     * Anbieter-Zuordnung für Mobilfunknummern
     */
    protected ?array $ndcMobileProviders = ['6' => 'Mobilfunk', '7' => 'Mobilfunk'];

    /**
     * Gibt den Standard-ISO-Ländercode für Deutschland zurück.
     */
    protected function getRegion(): string
    {
        return 'FR';
    }
}
