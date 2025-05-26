<?php

namespace App\Models\State;

enum MessageStateType: string
{
    case ERROR = 'error';
    case DEFAULT = 'default';
}
