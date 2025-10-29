<?php

namespace App\Models;

use App\Traits\HasUlid;
use Laratrust\Models\Permission as PermissionModel;

class Permission extends PermissionModel
{
    use HasUlid;
    
    public $guarded = [];
}
