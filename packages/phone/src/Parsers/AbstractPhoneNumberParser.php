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
abstract class AbstractPhoneNumberParser
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
        [$ndc, $subscriber] = $this->splitNdcSubscriber($rest);
        $type = $this->detectType($ndc);
        $this->validateLength($ndc, $subscriber);
        [$regionName, $provider] = $this->detectGeography($ndc, $type);

        // formatiert international mit Leerzeichen
        $formatted = "+{$this->countryCode} {$ndc} {$subscriber}" .
                     ($directDial ? " x{$directDial}" : "");

        return new PhoneNumber(
            phoneNumber: $raw,
            countryCode: $this->countryCode,
            ndc: $ndc,
            region: $regionName,
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
     * Entfernt unerlaubte Zeichen aus der Telefonnummer.
     */
    protected function normalize(string $input): string
    {
        return preg_replace('/[^0-9+]/', '', $input) ?: '';
    }

    /**
     * Trennt eine eventuell vorhandene Extension ab.
     */
    protected function extractExtension(string $input): array
    {
        // Match only if 'ext', 'x', or ';' are explicitly used to indicate an extension
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

    /**
     * Trennt NDC und Teilnehmernummer anhand erlaubter Längen.
     *
     * @throws CountryCodeParserException wenn keine gültige Kombination gefunden wird
     */
    protected function splitNdcSubscriber(string $rest): array
    {
        foreach ($this->ndcLengths as $len) {
            $ndc = substr($rest, 0, $len);
            $sub = substr($rest, $len);

            if (in_array(strlen($sub), $this->subscriberLengths, true)) {
                return [$ndc, $sub];
            }
        }

        throw new CountryCodeParserException('Ungültiges NDC/Subscriber-Format');
    }

    /**
     * Ermittelt den Typ der Nummer anhand des NDCs.
     */
    protected function detectType(string $ndc): ?PhoneNumberType
    {
        foreach ($this->ndcTypeRanges as $type => $ranges) {
            foreach ($ranges as $start => $end) {
                if ((int) $ndc >= $start && (int) $ndc <= $end) {
                    return PhoneNumberType::from($type);
                }
            }
        }

        return null;
    }

    /**
     * Überprüft, ob die Gesamtlänge nach E.164 gültig ist (zwischen 7 und 15 Ziffern).
     */
    protected function validateLength(string $ndc, string $sub): void
    {
        $length = strlen($this->countryCode.$ndc.$sub);

        if ($length < 7 || $length > 15) {
            throw new CountryCodeParserException("Ungültige Gesamtlänge: {$length}");
        }
    }

    /**
     * Liefert optionale geografische Informationen:
     * - Bei Festnetznummern: Region
     * - Bei Mobilnummern: Anbietername
     */
    protected function detectGeography(string $ndc, ?PhoneNumberType $type): array
    {
        $region = $this->getRegion();
        $provider = null;

        if ($type === PhoneNumberType::LANDLINE && $this->ndcFixedRegions && isset($this->ndcFixedRegions[$ndc])) {
            $region = $this->ndcFixedRegions[$ndc];
        }

        if ($type === PhoneNumberType::MOBILE && $this->ndcMobileProviders && isset($this->ndcMobileProviders[$ndc])) {
            $provider = $this->ndcMobileProviders[$ndc];
        }

        return [$region, $provider];
    }

    /**
     * Gibt die Standardregion zurück (z. B. ISO 3166-1 Alpha-2).
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
            // mb_chr erzeugt korrektes UTF-8 für Unicode-Codepoints
            $emoji .= function_exists('mb_chr')
                ? mb_chr($codepoint, 'UTF-8')
                : IntlChar::chr($codepoint);
        }
        return $emoji;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }
}
