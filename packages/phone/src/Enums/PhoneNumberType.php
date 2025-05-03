<?php

namespace Phone\Enums;

enum PhoneNumberType: string
{
    case MOBILE = 'mobile';
    case LANDLINE = 'landline';
    case SERVICE = 'service';
}
