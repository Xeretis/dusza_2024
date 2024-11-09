<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spiritix\LadaCache\Database\LadaCacheTrait;

class BaseModel extends Model
{
    use LadaCacheTrait;
}
