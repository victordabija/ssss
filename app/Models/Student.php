<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Builder idnp(string $idnp)
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

    public function getKeyName()
    {
        return 'idnp';
    }
}
