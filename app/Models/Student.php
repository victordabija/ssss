<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @method static Builder idnp(string $idnp)
 * @method static bool idnpExists(string $idnp)
 * @method static Builder recent()
 */
class Student extends Model
{
    protected $fillable = [
        'name',
        'group',
        'studyYear',
        'speciality',
        'idnp',
        'content',
    ];

    #[Scope]
    protected function idnp(Builder $query, string $idnp)
    {
        $query->where('idnp', $idnp);
    }

    #[Scope]
    protected function idnpExists(Builder $query, string $idnp)
    {
        $query->where('idnp', $idnp)->exists();
    }

    #[Scope]
    protected function recent(Builder $query)
    {
        $query->where('created_at', '>', Carbon::today()->subSecond()->toString());
    }
}
