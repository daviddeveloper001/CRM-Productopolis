<?php

namespace App\Enum;

enum FulfillmentStatusEnum: string
{
    case Pending = 'pending';
    case Fulfilled = 'fulfilled';

}