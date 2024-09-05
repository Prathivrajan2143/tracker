<?php

namespace App\Enums;

enum LoginType: string
{
    case General = 'general';
    case SSO = 'sso';
}
