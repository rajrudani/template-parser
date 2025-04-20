<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcessedDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use App\Services\DocumentParserService;
use Illuminate\Support\Facades\Storage;

class ProcessedDocumentController extends Controller
{
    protected $parserService;
    
    public function __construct(DocumentParserService $parserService)
    {
        $this->parserService = $parserService;
        $this->middleware('auth');
        $this->middleware('role:principal')->only(['edit', 'update']);
    }
    
    /**
     * Display a listing of processed documents for the current school
     */
    public function index()
    {
        $school = Auth::user()->school;
        $documents = ProcessedDocument::where('school_id', $school->id)->get();
        
        return view('processed.index', compact('documents'));
    }
    
    /**
     * Show the form for editing the specified document
     */
    public function edit(ProcessedDocument $document)
    {
        // Ensure the user belongs to this school
        if (Auth::user()->school_id !== $document->school_id) {
            abort(403);
        }
        
        $content = Storage::disk('public')->get($document->file_path);
        
        return view('processed.edit', compact('document', 'content'));
    }
    
    /**
     * Update the specified document
     */
    public function update(Request $request, ProcessedDocument $document)
    {
        // Ensure the user belongs to this school
        if (Auth::user()->school_id !== $document->school_id) {
            abort(403);
        }
        
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $this->parserService->updateEditedContent($document, $request->input('content'));
        
        return redirect()->route('processed.index')
            ->with('success', 'Document updated successfully.');
    }
    
    /**
     * Export the document in a specific format
     */
    public function export(Request $request, ProcessedDocument $document)
    {
        // Ensure the user belongs to this school
        if (Auth::user()->school_id !== $document->school_id) {
            abort(403);
        }
        
        $format = $request->input('format', 'pdf');
        $allowedFormats = ['pdf', 'docx'];
        
        if (!in_array($format, $allowedFormats)) {
            return redirect()->back()
                ->with('error', 'Invalid export format.');
        }
        
        $exportPath = $this->parserService->exportDocument($document, $format);
        
        return Storage::disk('public')->download($exportPath);
    }

}
