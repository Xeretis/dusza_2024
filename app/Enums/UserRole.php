<?php

namespace App\Enums;

enum UserRole: string
{
    case Competitor = "competitor";
    case Organizer = "organizer";
    case SchoolManager = "school-manager";
    case Teacher = "teacher";
}
