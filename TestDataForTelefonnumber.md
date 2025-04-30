## Testdaten zur Überprüfung des Telefonnummernformats

### Gültige Telefonnummern
| Unformatierte Eingabe     | Land                    | Richtig formatiertes Beispiel (DIN 5008) |
|---------------------------|-------------------------|------------------------------------------|
| 030 12 345 678            | Deutschland (Berlin)    | +49 30 12345678                           |
| (030)123 45678            | Deutschland (Berlin)    | +49 30 12345678                           |
| 0176-123-45678            | Deutschland (Mobil)     | +49 176 12345678                          |
| 089/1234567               | Deutschland (München)   | +49 89 1234567                            |
| +49 (221) 987 65 432      | Deutschland (Köln)      | +49 221 98765432                          |
| +1 (212) 555-7890         | USA (New York)          | +1 212 5557890                            |
| +44 20 7946 0958          | Vereinigtes Königreich  | +44 20 79460958                           |
| +33-1-45-67-89-01         | Frankreich (Paris)      | +33 1 45678901                            |
| +91 98 765 43210          | Indien (Mumbai)         | +91 98 76543210                           |
| +81(3)1234-5678           | Japan (Tokio)           | +81 3 12345678                            |
| +39 06/6988 1234          | Italien (Rom)           | +39 06 69881234                           |
| +61-2-9876-5432           | Australien (Sydney)     | +61 2 98765432                            |
| +34 91 123 45 67          | Spanien (Madrid)        | +34 91 1234567                            |
| +55 (11) 91234-5678       | Brasilien (São Paulo)   | +55 11 912345678                          |
| +7 495 123 45 67          | Russland (Moskau)       | +7 495 1234567                            |
| +46-8-123-456             | Schweden (Stockholm)    | +46 8 123456                              |
| +82 2 312 3456            | Südkorea (Seoul)        | +82 2 3123456                             |
| +31 (20) 123-4567         | Niederlande (Amsterdam) | +31 20 1234567                            |
| +86 10 1234 5678          | China (Peking)          | +86 10 12345678                           |

### Ungültige Telefonnummern
| Unformatierte Eingabe     | Land                    | Grund für Ungültigkeit            |
|---------------------------|-------------------------|-----------------------------------|
| 123456                    | Unbekannt               | Zu kurz                           |
| +49 89 12X4567            | Deutschland (München)   | Enthält Buchstaben                |
| 0049 (0)30 1234 5678      | Deutschland (Berlin)    | Doppelte Vorwahl (0049 und 0)     |
| +1-800-FLOWERS            | USA                     | Enthält Buchstaben (Vanity-Nummer)|
| +44-207-946-0958123       | Vereinigtes Königreich  | Zu lang                           |
| 089--1234567              | Deutschland (München)   | Doppelte Bindestriche             |
| +49(176)12_34_567         | Deutschland (Mobil)     | Unerlaubte Zeichen (Unterstriche) |
| +91 98765@43210           | Indien (Mumbai)         | Sonderzeichen enthalten           |
