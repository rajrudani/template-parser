<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'address', 'affiliation_no', 'school_code', 
        'email', 'contact', 'website', 'principal_name'
    ];

    public function events()
    {
        return $this->hasMany(SchoolEvent::class);
    }

    public function processedDocuments()
    {
        return $this->hasMany(ProcessedDocument::class);
    }
    
    // Get placeholder values for this school
    public function getPlaceholderValues()
    {
        return [
            'School Name' => $this->name,
            'School Address' => $this->address,
            'School Affiliation No.' => $this->affiliation_no,
            'School Code' => $this->school_code,
            'School Email' => $this->email,
            'School Contact' => $this->contact,
            'School Website' => $this->website,
            'Principal Name' => $this->principal_name,
        ];
    }

}
