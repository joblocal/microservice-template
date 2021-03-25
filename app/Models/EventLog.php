<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\EventLog
 *
 * @property integer $id
 * @property string $payload
 * @property-read string $publication_id
 * @property-read string $subject
 * @property-read string $event_at
 * @method static Builder|EventLog newModelQuery()
 * @method static Builder|EventLog newQuery()
 * @method static Builder|EventLog query()
 * @mixin Eloquent
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|EventLog whereCreatedAt($value)
 * @method static Builder|EventLog whereEventAt($value)
 * @method static Builder|EventLog whereId($value)
 * @method static Builder|EventLog wherePayload($value)
 * @method static Builder|EventLog wherePublicationId($value)
 * @method static Builder|EventLog whereSubject($value)
 * @method static Builder|EventLog whereUpdatedAt($value)
 */
class EventLog extends Model
{
    use HasFactory;

    public const SUBJECT_CREATED = 'created';
    public const SUBJECT_DELETED = 'deleted';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'payload',
        'publication_id',
        'subject',
        'event_at',
    ];

    public function shouldExecute()
    {
        $lastEvent = EventLog::where('publication_id', $this->publication_id)
            ->select('id')
            ->orderBy('event_at', 'desc')
            ->first();

        return $lastEvent && $lastEvent->id === $this->id;
    }
}
