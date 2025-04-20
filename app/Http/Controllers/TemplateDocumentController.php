<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\SchoolEvent;
use App\Models\TemplateDocument;
use App\Models\ProcessedDocument;
use App\Services\DocumentParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TemplateDocumentController extends Controller
{
    protected $parserService;
    
    public function __construct(DocumentParserService $parserService)
    {
        $this->parserService = $parserService;
    }
    
    /**
     * Display a listing of templates
     */
    public function index()
    {
        $templates = TemplateDocument::where('is_active', true)->get();
        return view('templates.index', compact('templates'));
    }
    
    /**
     * Show the form for creating a new template
     */
    public function create()
    {
        return view('templates.create');
    }
    
    /**
     * Store a newly created template
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document' => 'required|file|mimes:pdf,doc,docx,txt|max:10240',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $template = $this->parserService->processTemplateUpload(
            $request->file('document'),
            $request->input('title'),
            $request->input('description')
        );
        
        return redirect()->route('templates.index')
            ->with('success', 'Template uploaded successfully.');
    }
    
    /**
     * Display the specified template
     */
    public function show(TemplateDocument $template)
    {
        return view('templates.show', compact('template'));
    }
    
    /**
     * Remove the specified template
     */
    public function destroy(TemplateDocument $template)
    {
        // Soft delete by marking as inactive
        $template->update(['is_active' => false]);
        
        return redirect()->route('templates.index')
            ->with('success', 'Template removed successfully.');
    }
    
    /**
     * Preview template with school data
     */
    public function preview(Request $request, TemplateDocument $template)
    {
        $school = School::findOrFail($request->input('school_id'));
        $event = null;
        
        if ($request->has('event_id')) {
            $event = SchoolEvent::findOrFail($request->input('event_id'));
        }
        
        $document = $this->parserService->processDocumentForSchool($template, $school, $event);
        
        return redirect()->route('processed.edit', $document);
    }
}
