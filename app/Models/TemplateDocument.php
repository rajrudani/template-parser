<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TemplateDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'filename', 'original_filename', 
        'mime_type', 'file_path', 'placeholder_mapping', 'is_active'
    ];

    protected $casts = [
        'placeholder_mapping' => 'array',
        'is_active' => 'boolean'
    ];

    public function processedDocuments()
    {
        return $this->hasMany(ProcessedDocument::class);
    }

}
