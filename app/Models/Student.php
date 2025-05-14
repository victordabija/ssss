<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property string|null $name
 * @property string $idnp
 * @property string|null $group
 * @property int|null $studyYear
 * @property string|null $speciality
 * @property string|null $content
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder<static>|Student newModelQuery()
 * @method static Builder<static>|Student newQuery()
 * @method static Builder<static>|Student query()
 * @method static Builder<static>|Student recent()
 * @method static Builder<static>|Student whereContent($value)
 * @method static Builder<static>|Student whereCreatedAt($value)
 * @method static Builder<static>|Student whereGroup($value)
 * @method static Builder<static>|Student whereId($value)
 * @method static Builder<static>|Student whereIdnp($value)
 * @method static Builder<static>|Student whereName($value)
 * @method static Builder<static>|Student whereSpeciality($value)
 * @method static Builder<static>|Student whereStudyYear($value)
 * @method static Builder<static>|Student whereUpdatedAt($value)
 * @mixin \Eloquent
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
    protected function idnp(Builder $query, string $idnp): void
    {
        $query->where('idnp', $idnp);
    }

    #[Scope]
    protected function idnpExists(Builder $query, string $idnp): void
    {
        $query->where('idnp', $idnp)->exists();
    }

    #[Scope]
    protected function recent(Builder $query): void
    {
        $query->where('created_at', '>', Carbon::today()->subSecond()->toString());
    }
}
