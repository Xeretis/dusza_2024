<?php

namespace App\Enums;

enum CompetitorProfileType: int
{
    case Teacher = 0;
    case Student = 1;
    case SubstituteStudent = 2;
}
