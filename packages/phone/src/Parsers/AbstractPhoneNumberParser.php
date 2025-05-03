<?php

namespace Phone\Parsers;

use IntlChar;
use Phone\Enums\PhoneNumberType;
use Phone\Exceptions\CountryCodeParserException;
use Phone\PhoneNumber;

/**
 * Abstrakte Basisklasse für länderspezifische Telefonnummernparser.
 *
 * Diese Klasse enthält die allgemeine Logik zum Parsen, Normalisieren und Strukturieren
 * von Telefonnummern. Sie kann durch konkrete Länder-Parser erweitert werden, indem
 * die Metadaten überschrieben werden und die Methode getRegion() implementiert wird.
 */
abstract class AbstractPhoneNumberParser implements PhoneNumberParserInterface
{
    /** Ländervorwahl ohne '+' (z.B. "49" für Deutschland) */
    protected string $countryCode;

    /** Internationale Vorwahl (z.B. "00") */
    protected string $internationalPrefix;

    /** Nationale Vorwahl (z.B. "0") */
    protected string $nationalPrefix;

    /** Erlaubte Längen für die NDC (Vorwahl ohne Trunk) */
    protected array $ndcLengths;

    /** Erlaubte Längen für Teilnehmernummern */
    protected array $subscriberLengths;

    /** Typbereiche für die NDCs (z.B. Mobilnummern, Festnetz, Servicenummern) */
    protected array $ndcTypeRanges;

    /** Optional: Regionale Festnetzzuordnung nach NDC */
    protected ?array $ndcFixedRegions = null;

    /** Optional: Provider-Zuordnung für Mobilnummern nach NDC */
    protected ?array $ndcMobileProviders = null;

    /**
     * Hauptlogik zum Parsen einer Telefonnummer.
     *
     * @param  string  $input  Eingabe-Telefonnummer
     * @return PhoneNumber Strukturierte Telefonnummer
     *
     * @throws CountryCodeParserException bei ungültigem Format oder nicht parsbaren Daten
     */
    public function parse(string $input): PhoneNumber
    {
        $raw = $input;
        $normalized = $this->normalize($input);
        [$main, $directDial] = $this->extractExtension($normalized);
        $rest = $this->stripPrefix($main);

        [$type, $ndc, $region, $provider] = $this->matchNdcAndGeo($rest);

        $subscriber = substr($rest, strlen($ndc));

        // 3. Validierung der Gesamtlänge nach E.164 und NDC/Subscriber-Längen
        $this->validateLength($ndc, $subscriber);
        $this->validateNdcAndSubscriberLengths($ndc, $subscriber);

        $formatted = "+{$this->countryCode} {$ndc} {$subscriber}".
                     ($directDial ? " x{$directDial}" : '');

        return new PhoneNumber(
            phoneNumber: $raw,
            countryCode: $this->countryCode,
            ndc: $ndc,
            region: $region,
            mobileProvider: $provider,
            subscriberNumber: $subscriber,
            directDialingCode: $directDial,
            iso3166alpha2: $this->getRegion(),
            flag: $this->getFlagEmoji(),
            formattedPhone: $formatted,
            type: $type
        );
    }

    /**
     * Kombinierte Erkennung von Typ, NDC, Region und Provider.
     *
     * @return array{PhoneNumberType, string, string, string|null}
     *
     * @throws CountryCodeParserException
     */
    protected function matchNdcAndGeo(string $rest): array
    {
        // Merge keys from both region and provider arrays
        $possibleNdcs = array_unique(array_merge(
            array_keys($this->ndcFixedRegions ?? []),
            array_keys($this->ndcMobileProviders ?? [])
        ));

        // Sort by length descending to prioritize longer prefixes
        usort($possibleNdcs, fn ($a, $b) => strlen($b) <=> strlen($a));

        foreach ($possibleNdcs as $ndc) {
            if (str_starts_with($rest, $ndc)) {
                $region = $this->ndcFixedRegions[$ndc] ?? $this->getRegion();
                $provider = $this->ndcMobileProviders[$ndc] ?? null;
                $type = isset($this->ndcFixedRegions[$ndc])
                    ? PhoneNumberType::LANDLINE
                    : (isset($this->ndcMobileProviders[$ndc]) ? PhoneNumberType::MOBILE : PhoneNumberType::UNKNOWN);

                return [$type, $ndc, $region, $provider];
            }
        }

        throw new CountryCodeParserException("Keine passende NDC für: {$rest}");
    }

    /**
     * Entfernt unerlaubte Zeichen aus der Telefonnummer.
     */
    protected function normalize(string $input): string
    {
        return preg_replace('/[^0-9+]/', '', $input) ?: '';
    }

    /**
     * Trennt eine eventuell vorhandene Extension (Durchwahl) ab.
     */
    protected function extractExtension(string $input): array
    {
        if (preg_match('/(.*?)(?:\s*(?:ext|x|;)\s*(\d+))$/i', $input, $m)) {
            return [trim($m[1]), $m[2]];
        }

        return [$input, null];
    }

    /**
     * Entfernt internationale oder nationale Vorwahlpräfixe.
     */
    protected function stripPrefix(string $input): string
    {
        return match (true) {
            str_starts_with($input, "+{$this->countryCode}") => substr($input, strlen($this->countryCode) + 1),
            str_starts_with($input, $this->internationalPrefix.$this->countryCode) => substr($input, strlen($this->internationalPrefix.$this->countryCode)),
            str_starts_with($input, $this->nationalPrefix) => substr($input, strlen($this->nationalPrefix)),
            default => $input,
        };
    }

    protected function validateLength(string $ndc, string $sub): void
    {
        $length = strlen($this->countryCode.$ndc.$sub);
        if ($length < 7 || $length > 15) {
            throw new CountryCodeParserException("Ungültige Gesamtlänge: {$length}");
        }
    }

    protected function validateNdcAndSubscriberLengths(string $ndc, string $sub): void
    {
        if (! in_array(strlen($ndc), $this->ndcLengths, true) || ! in_array(strlen($sub), $this->subscriberLengths, true)) {
            throw new CountryCodeParserException('Ungültige NDC- oder Subscriber-Länge');
        }
    }

    /**
     * Gibt die Standardregion in ISO 3166-1 Alpha-2 zurück (z.B. DE)
     */
    abstract protected function getRegion(): string;

    /**
     * Liefert die Länderkürzel-Flagge als Emoji (z.B. DE → 🇩🇪).
     */
    protected function getFlagEmoji(): string
    {
        $emoji = '';
        foreach (str_split($this->getRegion()) as $char) {
            $codepoint = 0x1F1E6 + ord(strtoupper($char)) - ord('A');
            $emoji .= function_exists('mb_chr') ? mb_chr($codepoint, 'UTF-8') : IntlChar::chr($codepoint);
        }

        return $emoji;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }
}
