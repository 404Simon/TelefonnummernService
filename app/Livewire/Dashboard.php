<?php

namespace App\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;

class Dashboard extends Component
{
    public string $phone = '';

    // Einzelbestandteile
    public string $countryCode = '';

    public string $areaCode = '';

    public string $mainNumber = '';

    public string $formattedPhone = '';

    public ?string $countryName = null;

    public ?string $countryFlag = null;

    // evtl. in config auslagern
    private const COUNTRIES = [
        '49'  => ['name' => 'Deutschland',            'flag' => '🇩🇪'],
        '1'   => ['name' => 'Vereinigte Staaten',      'flag' => '🇺🇸'],
        '44'  => ['name' => 'Vereinigtes Königreich',  'flag' => '🇬🇧'],
        '33'  => ['name' => 'Frankreich',              'flag' => '🇫🇷'],
        '91'  => ['name' => 'Indien',                  'flag' => '🇮🇳'],
        '81'  => ['name' => 'Japan',                   'flag' => '🇯🇵'],
        '39'  => ['name' => 'Italien',                 'flag' => '🇮🇹'],
        '61'  => ['name' => 'Australien',              'flag' => '🇦🇺'],
        '34'  => ['name' => 'Spanien',                 'flag' => '🇪🇸'],
        '55'  => ['name' => 'Brasilien',               'flag' => '🇧🇷'],
        '7'   => ['name' => 'Russland',                'flag' => '🇷🇺'],
        '46'  => ['name' => 'Schweden',                'flag' => '🇸🇪'],
        '82'  => ['name' => 'Südkorea',                'flag' => '🇰🇷'],
        '31'  => ['name' => 'Niederlande',             'flag' => '🇳🇱'],
        '86'  => ['name' => 'China',                   'flag' => '🇨🇳'],
    ];


    protected array $rules = [
        'phone' => ['required', 'string', 'max:255',
            'regex:/^(?:(?:\\+|00)\\d{1,3}|0)(?:[ \\-\\/\\(\\)]*\\d+)+$/'],
    ];

    public function updatedPhone(): void
    {
        $this->validateOnly('phone');
        $this->parsePhone($this->phone);
        $this->formatDIN5008();
        $this->detectCountry();
    }

    private function parsePhone(string $input): void
    {
        // cleanup
        $normalized = Str::of($input)
            ->replace([' ', '-', '/', '(', ')'], '');

        // land extrahieren
        $prefixType = null;
        if (Str::startsWith($normalized, '+')) {
            $normalized = Str::after($normalized, '+');
            $prefixType = '+';
        } elseif (Str::startsWith($normalized, '00')) {
            $normalized = substr($normalized, 2);
            $prefixType = '00';
        } elseif (Str::startsWith($normalized, '0')) {
            $normalized = ltrim($normalized, '0');
            $prefixType = '0';
        }

        $this->countryCode = '49'; // Default Land
        foreach (array_keys(self::COUNTRIES) as $code) {
            if (($prefixType !== '0') && Str::startsWith($normalized, (string) $code)) {
                $this->countryCode = $code;
                $normalized = substr($normalized, strlen($code));
                break;
            }
        }

        $len = strlen($normalized);
        if ($len > 7) {
            $areaLen = $len - 7;
            $this->areaCode = substr($normalized, 0, $areaLen);
            $this->mainNumber = substr($normalized, $areaLen);
        } else {
            $this->areaCode = '';
            $this->mainNumber = $normalized;
        }
    }

    private function formatDIN5008(): void
    {
        // Hauptnummer in 2er-Blöcke teilen
        $blocks = str_split($this->mainNumber, 2);
        $main = implode(' ', $blocks);

        $formatted = '+'.$this->countryCode;
        if ($this->areaCode !== '') {
            $formatted .= ' '.$this->areaCode;
        }
        $formatted .= ' '.$main;

        $this->formattedPhone = $formatted;
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
