<?php

namespace App\Http\States;

enum From{
    case Create;
    case Update;
    case Delete;
}