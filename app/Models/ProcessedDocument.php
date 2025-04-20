<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcessedDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_document_id', 'school_id', 'school_event_id',
        'filename', 'file_path', 'edited_content'
    ];

    public function templateDocument()
    {
        return $this->belongsTo(TemplateDocument::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function schoolEvent()
    {
        return $this->belongsTo(SchoolEvent::class);
    }

}
