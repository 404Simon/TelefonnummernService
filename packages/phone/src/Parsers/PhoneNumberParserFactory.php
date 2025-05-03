<?php

namespace Phone\Parsers;

use Phone\Exceptions\CountryCodeParserException;
use Phone\PhoneNumber;

/**
 * Factory/Registry zum Parsen beliebiger Telefonnummern.
 *
 * Registriert länderspezifische Parser und wählt beim Aufruf von parse()
 * automatisch den passenden Parser anhand des erkannten Ländercodes.
 */
class PhoneNumberParserFactory implements PhoneNumberParserInterface
{
    /**
     * Liste der Parser-Klassen, die standardmäßig registriert werden.
     * Neue Parser hier hinzufügen.
     * @var string[]
     */
    protected array $defaultParserClasses = [
        GermanPhoneNumberParser::class,
    ];

    /** @var AbstractPhoneNumberParser[] indexed by countryCode */
    public array $parsers = [];

    /**
     * Initialisiert die Factory und registriert alle Default-Parser.
     */
    public function __construct()
    {
        foreach ($this->defaultParserClasses as $parserClass) {
            /** @var AbstractPhoneNumberParser $parser */
            $parser = new $parserClass();
            $this->registerParser($parser);
        }
    }

    /**
     * Registriert einen konkreten Parser.
     *
     * @param AbstractPhoneNumberParser $parser
     */
    public function registerParser(AbstractPhoneNumberParser $parser): void
    {
        $code = $parser->getCountryCode();
        $this->parsers[$code] = $parser;
    }

    /**
     * Versucht, anhand des Präfixes im Input den passenden Parser zu finden.
     *
     * @param string $input z.B. "+49 30 1234567" oder "0049..." oder "030..."
     * @return PhoneNumber
     * @throws CountryCodeParserException wenn kein Parser gefunden wurde
     */
    public function parse(string $phone): PhoneNumber
    {
        if (str_starts_with($phone, '+') || str_starts_with($phone, '00')) {
            $withoutPrefix = preg_replace('/^(\+|00)/', '', $phone);
            foreach ($this->parsers as $key => $value) {
                if (str_starts_with($withoutPrefix, $key)) {
                    return $this->parsers[$key]->parse($phone);
                }
            }
        } elseif (str_starts_with($phone, '0')) {
            // Default ist Deutschland
            return $this->parsers['49']->parse($phone);
        }
        throw new CountryCodeParserException("Kein Parser für Telefonnummer gefunden: $phone");
    }

    /**
     * Hilfsmethode: alle registrierten Parser zurückgeben.
     *
     * @return AbstractPhoneNumberParser[]
     */
    public function getRegisteredParsers(): array
    {
        return $this->parsers;
    }
}
