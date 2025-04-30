<?php

namespace App\Livewire;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use Livewire\Component;

class Dashboard extends Component
{
    public string $phone = '';

    public string $countryCode = '';

    public string $areaCode = '';

    public string $mainNumber = '';

    public string $formattedPhone = '';

    public ?string $countryName = null;

    public ?string $countryFlag = null;

    private const COUNTRIES = [
        '49' => ['name' => 'Deutschland',            'flag' => '🇩🇪'],
        '1' => ['name' => 'Vereinigte Staaten',      'flag' => '🇺🇸'],
        '44' => ['name' => 'Vereinigtes Königreich',  'flag' => '🇬🇧'],
        '33' => ['name' => 'Frankreich',              'flag' => '🇫🇷'],
        '91' => ['name' => 'Indien',                  'flag' => '🇮🇳'],
        '81' => ['name' => 'Japan',                   'flag' => '🇯🇵'],
        '39' => ['name' => 'Italien',                 'flag' => '🇮🇹'],
        '61' => ['name' => 'Australien',              'flag' => '🇦🇺'],
        '34' => ['name' => 'Spanien',                 'flag' => '🇪🇸'],
        '55' => ['name' => 'Brasilien',               'flag' => '🇧🇷'],
        '7' => ['name' => 'Russland',                'flag' => '🇷🇺'],
        '46' => ['name' => 'Schweden',                'flag' => '🇸🇪'],
        '82' => ['name' => 'Südkorea',                'flag' => '🇰�'],
        '31' => ['name' => 'Niederlande',             'flag' => '🇳🇱'],
        '86' => ['name' => 'China',                   'flag' => '🇨🇳'],
    ];

    protected array $rules = [
        'phone' => ['required', 'phone:AUTO'],
    ];

    public function updatedPhone(): void
    {
        $this->validateOnly('phone');

        try {
            $this->parsePhone($this->phone);
        } catch (NumberParseException $e) {
            $this->reset(['countryCode', 'areaCode', 'mainNumber', 'formattedPhone', 'countryName', 'countryFlag']);

            return;
        }

        $this->detectCountry();
    }

    private function parsePhone(string $input): void
    {
        $util = PhoneNumberUtil::getInstance();
        $number = $util->parse($input, null); // null = auto-detect region

        if (! $util->isValidNumber($number)) {
            throw new NumberParseException(NumberParseException::NOT_A_NUMBER, 'Invalid phone number');
        }

        $this->countryCode = (string) $number->getCountryCode();

        // National number zerlegen
        $nationalNumber = (string) $number->getNationalNumber();

        // Wir nehmen hier an: Hauptnummer = letzte 7 Stellen
        if (strlen($nationalNumber) > 7) {
            $this->areaCode = substr($nationalNumber, 0, -7);
            $this->mainNumber = substr($nationalNumber, -7);
        } else {
            $this->areaCode = '';
            $this->mainNumber = $nationalNumber;
        }

        // Format DIN 5008 ähnlich
        $main = implode(' ', str_split($this->mainNumber, 2));
        $this->formattedPhone = '+'.$this->countryCode.
            ($this->areaCode ? ' '.$this->areaCode : '').
            ' '.$main;
    }

    private function detectCountry(): void
    {
        if (isset(self::COUNTRIES[$this->countryCode])) {
            $this->countryName = self::COUNTRIES[$this->countryCode]['name'];
            $this->countryFlag = self::COUNTRIES[$this->countryCode]['flag'];
        } else {
            $this->countryName = null;
            $this->countryFlag = null;
        }
    }
}
