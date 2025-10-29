<?php

namespace App\Models;

use App\Traits\HasUlid;
use Laratrust\Models\Role as RoleModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends RoleModel
{
    use HasUlid;
    
    public $guarded = [];

    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'menu_role');
    }
}
