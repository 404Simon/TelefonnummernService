## Testdaten zur Überprüfung des Telefonnummernformats

### Gültige Telefonnummern
| Unformatierte Eingabe     | Land                    | Richtig formatiertes Beispiel |
|---------------------------|-------------------------|-------------------------------|
| 030 12 345 678            | Deutschland (Berlin)    | +49 3012345678                |
| (030)123 45678            | Deutschland (Berlin)    | +49 3012345678                |
| 0176-123-45678            | Deutschland (Mobil)     | +49 17612345678               |
| 089/1234567               | Deutschland (München)   | +49 891234567                 |
| +49 (221) 987 65 432      | Deutschland (Köln)      | +49 22198765432               |
| +1 (212) 555-7890         | USA (New York)          | +1 2125557890                 |
| +44 20 7946 0958          | Vereinigtes Königreich  | +44 2079460958                |
| +33-1-45-67-89-01         | Frankreich (Paris)      | +33 145678901                 |
| +91 98 765 43210          | Indien (Mumbai)         | +91 9876543210                |
| +81(3)1234-5678           | Japan (Tokio)           | +81 312345678                 |
| +39 06/6988 1234          | Italien (Rom)           | +39 0669881234                |
| +61-2-9876-5432           | Australien (Sydney)     | +61 298765432                 |
| +34 91 123 45 67          | Spanien (Madrid)        | +34 911234567                 |
| +55 (11) 91234-5678       | Brasilien (São Paulo)   | +55 11912345678               |
| +7 495 123 45 67          | Russland (Moskau)       | +7 4951234567                 |
| +46-8-123-456             | Schweden (Stockholm)    | +46 8123456                   |
| +82 2 312 3456            | Südkorea (Seoul)        | +82 23123456                  |
| +31 (20) 123-4567         | Niederlande (Amsterdam) | +31 201234567                 |
| +86 10 1234 5678          | China (Peking)          | +86 1012345678                |

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
