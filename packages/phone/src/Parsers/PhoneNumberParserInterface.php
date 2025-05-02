<?php

namespace Phone\Parsers;

use Phone\PhoneNumber;

interface PhoneNumberParserInterface
{
    public function parse(string $phone): PhoneNumber;
}
