<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id', 'event_name', 'event_date', 'venue', 'time', 'subject'
    ];

    protected $casts = [
        'event_date' => 'date',
        'time' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function processedDocuments()
    {
        return $this->hasMany(ProcessedDocument::class);
    }
    
    // Get placeholder values for this event
    public function getPlaceholderValues()
    {
        return [
            'Event Date' => $this->event_date ? $this->event_date->format('d/m/Y') : '',
            'Venue' => $this->venue,
            'Time' => $this->time ? $this->time->format('h:i A') : '',
            'Subject' => $this->subject,
        ];
    }
}
