<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserGroup extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'title'
    ];

    protected $appends = [
        'mapped_permissions'
    ];

    /**
     * @return HasMany
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * @return array
     */
    public function getMappedPermissionsAttribute(): array
    {
        if (!$this->relationLoaded('permissions')) {
            return [];
        }

        return $this->permissions->groupBy('group')->mapWithKeys(static function ($permissions, $groupName) {
            return [$groupName => $permissions->mapWithKeys(static function ($permission) {
                return [$permission->action => $permission->value];
            })];
        })->toArray();
    }
}
