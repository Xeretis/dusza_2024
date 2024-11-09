<?php

namespace App\Enums;

enum TeamEventResponseStatus: string
{
    case Pending = "pending";
    case Approved = "approved";
    case Rejected = "rejected";
}
