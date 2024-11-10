<?php

namespace App\Enums;

enum TeamEventType: string
{
    case Approval = 'approval';
    case AmendRequest = 'amend_request';
}
