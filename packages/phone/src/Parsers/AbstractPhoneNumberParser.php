<?php

namespace Phone\Parsers;

use Phone\Exceptions\CountryCodeParserException;
use Phone\PhoneNumber;

abstract class AbstractPhoneNumberParser
{
    private const COUNTRIES = [
        '49' => ['name' => 'Deutschland', 'iso3166alpha2' => 'DE', 'flag' => '🇩🇪'],
        '1' => ['name' => 'Vereinigte Staaten', 'iso3166alpha2' => 'US', 'flag' => '🇺🇸'],
        '1-242' => ['name' => 'Bahamas', 'iso3166alpha2' => 'BS', 'flag' => '🇧🇸'],
        '1-246' => ['name' => 'Barbados', 'iso3166alpha2' => 'BB', 'flag' => '🇧🇧'],
        '1-264' => ['name' => 'Anguilla', 'iso3166alpha2' => 'AI', 'flag' => '🇦🇮'],
        '1-268' => ['name' => 'Antigua und Barbuda', 'iso3166alpha2' => 'AG', 'flag' => '🇦🇬'],
        '1-284' => ['name' => 'Britische Jungferninseln', 'iso3166alpha2' => 'VG', 'flag' => '🇻🇬'],
        '1-340' => ['name' => 'Amerikanische Jungferninseln', 'iso3166alpha2' => 'VI', 'flag' => '🇻🇮'],
        '1-345' => ['name' => 'Kaimaninseln', 'iso3166alpha2' => 'KY', 'flag' => '🇰🇾'],
        '1-441' => ['name' => 'Bermuda', 'iso3166alpha2' => 'BM', 'flag' => '🇧🇲'],
        '1-473' => ['name' => 'Grenada', 'iso3166alpha2' => 'GD', 'flag' => '🇬🇩'],
        '1-649' => ['name' => 'Turks- und Caicosinseln', 'iso3166alpha2' => 'TC', 'flag' => '🇹🇨'],
        '1-664' => ['name' => 'Montserrat', 'iso3166alpha2' => 'MS', 'flag' => '🇲🇸'],
        '1-670' => ['name' => 'Nördliche Marianen', 'iso3166alpha2' => 'MP', 'flag' => '🇲🇵'],
        '1-671' => ['name' => 'Guam', 'iso3166alpha2' => 'GU', 'flag' => '🇬🇺'],
        '1-684' => ['name' => 'Amerikanisch-Samoa', 'iso3166alpha2' => 'AS', 'flag' => '🇦🇸'],
        '1-758' => ['name' => 'St. Lucia', 'iso3166alpha2' => 'LC', 'flag' => '🇱🇨'],
        '1-767' => ['name' => 'Dominica', 'iso3166alpha2' => 'DM', 'flag' => '🇩🇲'],
        '1-784' => ['name' => 'St. Vincent und die Grenadinen', 'iso3166alpha2' => 'VC', 'flag' => '🇻🇨'],
        '1-787' => ['name' => 'Puerto Rico', 'iso3166alpha2' => 'PR', 'flag' => '🇵🇷'],
        '1-809' => ['name' => 'Dominikanische Republik', 'iso3166alpha2' => 'DO', 'flag' => '🇩🇴'],
        '1-829' => ['name' => 'Dominikanische Republik', 'iso3166alpha2' => 'DO', 'flag' => '🇩🇴'],
        '1-849' => ['name' => 'Dominikanische Republik', 'iso3166alpha2' => 'DO', 'flag' => '🇩🇴'],
        '1-868' => ['name' => 'Trinidad und Tobago', 'iso3166alpha2' => 'TT', 'flag' => '🇹🇹'],
        '1-869' => ['name' => 'St. Kitts und Nevis', 'iso3166alpha2' => 'KN', 'flag' => '🇰🇳'],
        '1-876' => ['name' => 'Jamaika', 'iso3166alpha2' => 'JM', 'flag' => '🇯🇲'],
        '7' => ['name' => 'Russland', 'iso3166alpha2' => 'RU', 'flag' => '🇷🇺'],
        '20' => ['name' => 'Ägypten', 'iso3166alpha2' => 'EG', 'flag' => '🇪🇬'],
        '27' => ['name' => 'Südafrika', 'iso3166alpha2' => 'ZA', 'flag' => '🇿🇦'],
        '30' => ['name' => 'Griechenland', 'iso3166alpha2' => 'GR', 'flag' => '🇬🇷'],
        '31' => ['name' => 'Niederlande', 'iso3166alpha2' => 'NL', 'flag' => '🇳🇱'],
        '32' => ['name' => 'Belgien', 'iso3166alpha2' => 'BE', 'flag' => '🇧🇪'],
        '33' => ['name' => 'Frankreich', 'iso3166alpha2' => 'FR', 'flag' => '🇫🇷'],
        '34' => ['name' => 'Spanien', 'iso3166alpha2' => 'ES', 'flag' => '🇪🇸'],
        '36' => ['name' => 'Ungarn', 'iso3166alpha2' => 'HU', 'flag' => '🇭🇺'],
        '39' => ['name' => 'Italien', 'iso3166alpha2' => 'IT', 'flag' => '🇮🇹'],
        '40' => ['name' => 'Rumänien', 'iso3166alpha2' => 'RO', 'flag' => '🇷🇴'],
        '41' => ['name' => 'Schweiz', 'iso3166alpha2' => 'CH', 'flag' => '🇨🇭'],
        '43' => ['name' => 'Österreich', 'iso3166alpha2' => 'AT', 'flag' => '🇦🇹'],
        '44' => ['name' => 'Vereinigtes Königreich', 'iso3166alpha2' => 'GB', 'flag' => '🇬🇧'],
        '45' => ['name' => 'Dänemark', 'iso3166alpha2' => 'DK', 'flag' => '🇩🇰'],
        '46' => ['name' => 'Schweden', 'iso3166alpha2' => 'SE', 'flag' => '🇸🇪'],
        '47' => ['name' => 'Norwegen', 'iso3166alpha2' => 'NO', 'flag' => '🇳🇴'],
        '48' => ['name' => 'Polen', 'iso3166alpha2' => 'PL', 'flag' => '🇵🇱'],
        '51' => ['name' => 'Peru', 'iso3166alpha2' => 'PE', 'flag' => '🇵🇪'],
        '52' => ['name' => 'Mexiko', 'iso3166alpha2' => 'MX', 'flag' => '🇲🇽'],
        '53' => ['name' => 'Kuba', 'iso3166alpha2' => 'CU', 'flag' => '🇨🇺'],
        '54' => ['name' => 'Argentinien', 'iso3166alpha2' => 'AR', 'flag' => '🇦🇷'],
        '55' => ['name' => 'Brasilien', 'iso3166alpha2' => 'BR', 'flag' => '🇧🇷'],
        '56' => ['name' => 'Chile', 'iso3166alpha2' => 'CL', 'flag' => '🇨🇱'],
        '57' => ['name' => 'Kolumbien', 'iso3166alpha2' => 'CO', 'flag' => '🇨🇴'],
        '58' => ['name' => 'Venezuela', 'iso3166alpha2' => 'VE', 'flag' => '🇻🇪'],
        '60' => ['name' => 'Malaysia', 'iso3166alpha2' => 'MY', 'flag' => '🇲🇾'],
        '61' => ['name' => 'Australien', 'iso3166alpha2' => 'AU', 'flag' => '🇦🇺'],
        '62' => ['name' => 'Indonesien', 'iso3166alpha2' => 'ID', 'flag' => '🇮🇩'],
        '63' => ['name' => 'Philippinen', 'iso3166alpha2' => 'PH', 'flag' => '🇵🇭'],
        '64' => ['name' => 'Neuseeland', 'iso3166alpha2' => 'NZ', 'flag' => '🇳🇿'],
        '65' => ['name' => 'Singapur', 'iso3166alpha2' => 'SG', 'flag' => '🇸🇬'],
        '66' => ['name' => 'Thailand', 'iso3166alpha2' => 'TH', 'flag' => '🇹🇭'],
        '81' => ['name' => 'Japan', 'iso3166alpha2' => 'JP', 'flag' => '🇯🇵'],
        '82' => ['name' => 'Südkorea', 'iso3166alpha2' => 'KR', 'flag' => '🇰🇷'],
        '84' => ['name' => 'Vietnam', 'iso3166alpha2' => 'VN', 'flag' => '🇻🇳'],
        '86' => ['name' => 'China', 'iso3166alpha2' => 'CN', 'flag' => '🇨🇳'],
        '90' => ['name' => 'Türkei', 'iso3166alpha2' => 'TR', 'flag' => '🇹🇷'],
        '91' => ['name' => 'Indien', 'iso3166alpha2' => 'IN', 'flag' => '🇮🇳'],
        '92' => ['name' => 'Pakistan', 'iso3166alpha2' => 'PK', 'flag' => '🇵🇰'],
        '93' => ['name' => 'Afghanistan', 'iso3166alpha2' => 'AF', 'flag' => '🇦🇫'],
        '94' => ['name' => 'Sri Lanka', 'iso3166alpha2' => 'LK', 'flag' => '🇱🇰'],
        '95' => ['name' => 'Myanmar', 'iso3166alpha2' => 'MM', 'flag' => '🇲🇲'],
        '971' => ['name' => 'Vereinigte Arabische Emirate', 'iso3166alpha2' => 'AE', 'flag' => '🇦🇪'],
        '972' => ['name' => 'Israel', 'iso3166alpha2' => 'IL', 'flag' => '🇮🇱'],
        '973' => ['name' => 'Bahrain', 'iso3166alpha2' => 'BH', 'flag' => '🇧🇭'],
        '974' => ['name' => 'Katar', 'iso3166alpha2' => 'QA', 'flag' => '🇶🇦'],
        '975' => ['name' => 'Bhutan', 'iso3166alpha2' => 'BT', 'flag' => '🇧🇹'],
        '976' => ['name' => 'Mongolei', 'iso3166alpha2' => 'MN', 'flag' => '🇲🇳'],
        '977' => ['name' => 'Nepal', 'iso3166alpha2' => 'NP', 'flag' => '🇳🇵'],
        '960' => ['name' => 'Malediven', 'iso3166alpha2' => 'MV', 'flag' => '🇲🇻'],
        '961' => ['name' => 'Libanon', 'iso3166alpha2' => 'LB', 'flag' => '🇱🇧'],
        '962' => ['name' => 'Jordanien', 'iso3166alpha2' => 'JO', 'flag' => '🇯🇴'],
        '963' => ['name' => 'Syrien', 'iso3166alpha2' => 'SY', 'flag' => '🇸🇾'],
        '964' => ['name' => 'Irak', 'iso3166alpha2' => 'IQ', 'flag' => '🇮🇶'],
        '965' => ['name' => 'Kuwait', 'iso3166alpha2' => 'KW', 'flag' => '🇰🇼'],
        '966' => ['name' => 'Saudi-Arabien', 'iso3166alpha2' => 'SA', 'flag' => '🇸🇦'],
        '967' => ['name' => 'Jemen', 'iso3166alpha2' => 'YE', 'flag' => '🇾🇪'],
        '968' => ['name' => 'Oman', 'iso3166alpha2' => 'OM', 'flag' => '🇴🇲'],
        '992' => ['name' => 'Tadschikistan', 'iso3166alpha2' => 'TJ', 'flag' => '🇹🇯'],
        '993' => ['name' => 'Turkmenistan', 'iso3166alpha2' => 'TM', 'flag' => '🇹🇲'],
        '994' => ['name' => 'Aserbaidschan', 'iso3166alpha2' => 'AZ', 'flag' => '🇦🇿'],
        '995' => ['name' => 'Georgien', 'iso3166alpha2' => 'GE', 'flag' => '🇬🇪'],
        '996' => ['name' => 'Kirgisistan', 'iso3166alpha2' => 'KG', 'flag' => '🇰🇬'],
        '998' => ['name' => 'Usbekistan', 'iso3166alpha2' => 'UZ', 'flag' => '🇺🇿'],
        '213' => ['name' => 'Algerien', 'iso3166alpha2' => 'DZ', 'flag' => '🇩🇿'],
        '216' => ['name' => 'Tunesien', 'iso3166alpha2' => 'TN', 'flag' => '🇹🇳'],
        '218' => ['name' => 'Libyen', 'iso3166alpha2' => 'LY', 'flag' => '🇱🇾'],
        '220' => ['name' => 'Gambia', 'iso3166alpha2' => 'GM', 'flag' => '🇬🇲'],
        '221' => ['name' => 'Senegal', 'iso3166alpha2' => 'SN', 'flag' => '🇸🇳'],
        '222' => ['name' => 'Mauretanien', 'iso3166alpha2' => 'MR', 'flag' => '🇲🇷'],
        '223' => ['name' => 'Mali', 'iso3166alpha2' => 'ML', 'flag' => '🇲🇱'],
        '224' => ['name' => 'Guinea', 'iso3166alpha2' => 'GN', 'flag' => '🇬🇳'],
        '225' => ['name' => 'Elfenbeinküste', 'iso3166alpha2' => 'CI', 'flag' => '🇨🇮'],
        '226' => ['name' => 'Burkina Faso', 'iso3166alpha2' => 'BF', 'flag' => '🇧🇫'],
        '227' => ['name' => 'Niger', 'iso3166alpha2' => 'NE', 'flag' => '🇳🇪'],
        '228' => ['name' => 'Togo', 'iso3166alpha2' => 'TG', 'flag' => '🇹🇬'],
        '229' => ['name' => 'Benin', 'iso3166alpha2' => 'BJ', 'flag' => '🇧🇯'],
        '230' => ['name' => 'Mauritius', 'iso3166alpha2' => 'MU', 'flag' => '🇲🇺'],
        '231' => ['name' => 'Liberia', 'iso3166alpha2' => 'LR', 'flag' => '🇱🇷'],
        '232' => ['name' => 'Sierra Leone', 'iso3166alpha2' => 'SL', 'flag' => '🇸🇱'],
    ];

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    protected function cleanPhoneNumber(string $phone): string
    {
        // Remove all non-digit characters
        return preg_replace('/\D/', '', $phone);
    }

    public static function getCountryCode(string $phone): string
    {
        // Extract the country code from the phone number
        if (str_starts_with($phone, '+') || str_starts_with($phone, '00')) {
            // Remove 00 or + with regex
            $phone = preg_replace('/^(\+|00)/', '', $phone);
            // Try to match a key of COUNTRIES in the phone number
            foreach (self::COUNTRIES as $key => $value) {
                if (str_starts_with($phone, $key)) {
                    return $key;
                }
            }
        } elseif (str_starts_with($phone, '0')) {
            // If there is no country code return 49 as default for Germany
            return '49';
        }

        throw new CountryCodeParserException;
    }

    public function parse(string $phone): PhoneNumber
    {
        // This method should be implemented in the child class
        throw new \Exception('Method not implemented');
    }
}
