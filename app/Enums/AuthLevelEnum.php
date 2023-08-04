<?php

namespace App\Enums;

enum AuthLevelEnum: int
{
    case User = 0;
    case Manager = 1;
    case Admin = 2;
    case SuperAdmin = 3;
}
