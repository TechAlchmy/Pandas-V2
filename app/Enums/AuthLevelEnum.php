<?php

namespace App\Enums;

enum AuthLevelEnum: int
{
    case User = 0;
    case Admin = 1;
    case SuperAdmin = 2;
}
