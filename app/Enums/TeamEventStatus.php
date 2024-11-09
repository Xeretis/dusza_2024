<?php

namespace App\Enums;

enum TeamEventStatus: string
{
    case Pending = "pending";
    case Completed = "completed";
    case Approved = "approved";
    case Rejected = "rejected";
}
