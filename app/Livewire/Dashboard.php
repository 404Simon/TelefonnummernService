<?php

namespace App\Livewire;

use Livewire\Component;
use Phone\Exceptions\CountryCodeParserException;
use Phone\Parsers\PhoneNumberParserFactory;

class Dashboard extends Component
{
    public string $phone = '';

    public ?array $phoneNumber = null;

    protected array $rules = [
        'phone' => [
            'required',
            'string',
            'not_regex:/[a-zA-Z]/',
            'regex:/^[0-9+\-\s()]+$/',
        ],
    ];

    public function updatedPhone(PhoneNumberParserFactory $parser): void
    {
        $this->validateOnly('phone');
        try {
            $this->phoneNumber = $parser->parse($this->phone)->toArray();
        } catch (CountryCodeParserException $th) {
            $this->phoneNumber = null;
        }
    }
}
