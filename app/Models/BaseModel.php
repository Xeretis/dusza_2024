<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;
use Spiritix\LadaCache\Database\LadaCacheTrait;

class BaseModel extends Model implements ContractsAuditable
{
    use LadaCacheTrait, Auditable;
}
