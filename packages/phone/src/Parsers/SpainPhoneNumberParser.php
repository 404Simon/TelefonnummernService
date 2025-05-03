<?php

namespace Phone\Parsers;

/**
 * Länderspezifischer Parser für deutsche Telefonnummern.
 *
 * Enthält Konfiguration für Vorwahlen, Längen, Typbereiche sowie optionale
 * regionale und Anbieter-Zuordnungen.
 */
class SpainPhoneNumberParser extends AbstractPhoneNumberParser
{
    protected string $countryCode = '34';

    protected string $internationalPrefix = '00';

    protected string $nationalPrefix = '0';

    /**
     * Typische NDC-Längen in Deutschland: 2-5 Ziffern
     */
    protected array $ndcLengths = [2, 3];

    /**
     * Teilnehmernummern können typischerweise zwischen 3 und 11 Ziffern haben
     */
    protected array $subscriberLengths = [6, 7, 8];

    /**
     * Bereiche für verschiedene Nummerntypen basierend auf der NDC
     */
    protected array $ndcTypeRanges = [];

    /**
     * Feste Region-Zuordnungen für bekannte Festnetzbereiche
     */
    protected ?array $ndcFixedRegions = [
        '822' => 'Santa Cruz de Tenerife',
        '824' => 'Badajoz',
        '828' => 'Las Palmas',
        '843' => 'Gipuzkoa',
        '848' => 'Navarre',
        '850' => 'Almería',
        '856' => 'Cádiz',
        '858' => 'Granada',
        '868' => 'Murcia',
        '871' => 'Balearic Islands',
        '872' => 'Girona',
        '873' => 'Lleida',
        '876' => 'Zaragoza',
        '877' => 'Tarragona',
        '881' => 'A Coruña',
        '882' => 'Lugo',
        '886' => 'Pontevedra',
        '900' => 'Toll Free',
        '901' => 'Shared-cost call',
        '902' => 'National Rate',
        '905' => 'Telephone Voting System',
        '907' => 'Premium Rate (data systems)',
        '908' => 'Internet Access',
        '909' => 'Internet Access',
        '911' => 'Madrid (Segovia and Guadalajara until 1993)',
        '912' => 'Madrid',
        '913' => 'Madrid',
        '914' => 'Madrid',
        '915' => 'Madrid',
        '916' => 'Madrid',
        '917' => 'Madrid',
        '918' => 'Madrid (Ávila until 1993)',
        '920' => 'Ávila',
        '921' => 'Segovia',
        '922' => 'Santa Cruz de Tenerife',
        '923' => 'Salamanca',
        '924' => 'Badajoz',
        '925' => 'Toledo',
        '926' => 'Ciudad Real',
        '927' => 'Cáceres',
        '928' => 'Las Palmas',
        '931' => 'Barcelona',
        '932' => 'Barcelona',
        '933' => 'Barcelona',
        '934' => 'Barcelona',
        '935' => 'Barcelona',
        '936' => 'Barcelona',
        '937' => 'Barcelona',
        '938' => 'Barcelona',
        '940' => 'Pager Services',
        '941' => 'La Rioja',
        '942' => 'Cantabria',
        '943' => 'Gipuzkoa',
        '944' => 'Biscay',
        '945' => 'Álava',
        '946' => 'Biscay',
        '947' => 'Burgos',
        '948' => 'Navarre',
        '949' => 'Guadalajara',
        '950' => 'Almería',
        '951' => 'Málaga',
        '952' => 'Málaga',
        '953' => 'Jaén',
        '954' => 'Seville',
        '955' => 'Seville',
        '956' => 'Cádiz',
        '957' => 'Córdoba',
        '958' => 'Granada',
        '959' => 'Huelva',
        '960' => 'Valencia',
        '961' => 'Valencia, Center of province',
        '962' => 'Valencia, South of province',
        '963' => 'Valencia, City and surroundings',
        '964' => 'Castellón',
        '965' => 'Alicante',
        '966' => 'Alicante',
        '967' => 'Albacete',
        '968' => 'Murcia',
        '969' => 'Cuenca',
        '971' => 'Balearic Islands',
        '972' => 'Girona',
        '973' => 'Lleida',
        '974' => 'Huesca',
        '975' => 'Soria',
        '976' => 'Zaragoza',
        '977' => 'Tarragona',
        '978' => 'Teruel',
        '979' => 'Palencia',
        '980' => 'Zamora',
        '981' => 'A Coruña',
        '982' => 'Lugo',
        '983' => 'Valladolid',
        '984' => 'Asturias',
        '985' => 'Asturias',
        '986' => 'Pontevedra',
        '987' => 'León',
        '988' => 'Ourense',
    ];

    /**
     * Anbieter-Zuordnung für Mobilfunknummern
     */
    protected ?array $ndcMobileProviders = [
        '6' => 'Mobile phones',
        '7' => 'Personal Numbering System',
        '71' => 'Mobile phones',
        '72' => 'Mobile phones',
        '73' => 'Mobile phones',
        '74' => 'Mobile phones',
        '75' => 'Mobile phones',
        '76' => 'Mobile phones',
        '77' => 'Mobile phones',
        '78' => 'Mobile phones',
        '79' => 'Mobile phones',
        '800' => 'Toll Free',
        '803' => 'Premium Rate (adult services)',
        '806' => 'Premium Rate (entertaining service)',
        '807' => 'Premium Rate (professional services)',
    ];

    /**
     * Gibt den Standard-ISO-Ländercode für Deutschland zurück.
     */
    protected function getRegion(): string
    {
        return 'DE';
    }
}
