# Telefonnummernservice

## Setup

Get started by installing PHP, Laravel, Composer and Node.js using the [Docs](https://laravel.com/docs/12.x/installation).

In Windows execute the following command to install PHP and Composer: 

``` PowerShell
# Run as administrator...
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.4'))
```

Then to install Laravel Installer execute this command:

``` PowerShell
composer global require laravel/installer
```

To setup all dependencies, type `composer run setup`.
Perfect, now you can start the backend by typing `composer run dev`.
The Application is now running at `localhost:8000`. 



## Documentation

### RegEx for Phonenumber Validation

```PHP
^
  (?:
    (?:+|00)\d{1,3}      # Ländervorwahl mit + oder 00, z.B. +49 oder 0049
    |
    [+\d{1,3}]         # oder in eckigen Klammern: [+49]
    |
    0|(0)              # oder nationale Vorwahl 0 bzw. (0)
  )
  (?:                    # danach eine oder mehrere Zifferngruppen …
    [ /-]*             # … jeweils optional getrennt durch Leerzeichen, / oder -
    (?:
      (?\d+)?          #   – einfache Zifferngruppe, optional in runden Klammern, z.B. (941) oder 201
      |
      [\d+(?:[/-]\d+)] #   – oder in eckigen Klammern mit internen - oder /, z.B. [990-477]
      |
      \d+(?:[/-]\d+)     #   – oder plain Ziffern mit internen - oder /, z.B. 790-4780 oder 89-800/849-50
    )
  )+
$
```