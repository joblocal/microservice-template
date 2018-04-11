<?php

/**
 * @property integer $id
 * @property string  $payload
 * @property string  $publication_id
 * @property string  $subject
 * @property string  $event_at
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Validation;

class EventLog extends Model
{
    use Validation;

    const SUBJECT_CREATED = 'created';
    const SUBJECT_DELETED = 'deleted';

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

    /**
     * Returns the validation rules
     * @return [array] The rules for this model (see: Illuminate\Validator\Validator:setRules)
     */
    public function rules()
    {
        return [
            'payload' => 'required|json',
            'publication_id' => 'required',
            'subject' => 'required|in:created,deleted',
            'event_at' => 'required|date_format:' . \DateTime::ISO8601,
        ];
    }

    public function shouldExecute()
    {
        $lastEvent = EventLog::where('publication_id', $this->publication_id)
            ->select('id')
            ->orderBy('event_at', 'desc')
            ->first();

        return ($lastEvent->id == $this->id);
    }
}
