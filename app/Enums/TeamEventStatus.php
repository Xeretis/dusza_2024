<?php

namespace App\Enums;

enum TeamEventStatus: string
{
    case Pending = "pending";
    case Approved = "approved";
    case Rejected = "rejected";
}
