<?php
namespace App\Services;

use App\Models\School;
use App\Models\SchoolEvent;
use App\Models\TemplateDocument;
use App\Models\ProcessedDocument;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use PhpOffice\PhpWord\PhpWord;
use Spatie\PdfToText\Pdf;
use PhpOffice\PhpWord\Writer\PDF as PDFWriter;
use Illuminate\Support\Str;

class DocumentParserService
{
    /**
     * Process an uploaded template document and extract placeholders
     */
    public function processTemplateUpload($file, $title, $description = null)
    {
        // Store the file
        $originalFilename = $file->getClientOriginalName();
        $mimeType = $file->getMimeType();
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . Str::slug(pathinfo($originalFilename, PATHINFO_FILENAME)) . '.' . $extension;
        
        $path = $file->storeAs('templates', $filename, 'public');
        
        // Extract content and detect placeholders
        $content = $this->extractContent($path, $mimeType);
        $placeholders = $this->detectPlaceholders($content);
        
        // Create template document record
        return TemplateDocument::create([
            'title' => $title,
            'description' => $description,
            'filename' => $filename,
            'original_filename' => $originalFilename,
            'mime_type' => $mimeType,
            'file_path' => $path,
            'placeholder_mapping' => $placeholders,
        ]);
    }
    
    /**
     * Extract content from various file types
     */
    private function extractContent($path, $mimeType)
    {
        $fullPath = Storage::disk('public')->path($path);
        
        if (Str::contains($mimeType, 'pdf')) {
            return (new Pdf())
                ->setPdf($fullPath)
                ->text();
        }
        
        if (Str::contains($mimeType, 'word') || in_array(pathinfo($path, PATHINFO_EXTENSION), ['doc', 'docx'])) {
            $phpWord = WordIOFactory::load($fullPath);
            $content = '';
            
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $content .= $element->getText() . "\n";
                    }
                }
            }
            
            return $content;
        }
        
        // Default to reading the file as text
        return Storage::disk('public')->get($path);
    }
    
    /**
     * Detect placeholders in content using regex
     */
    private function detectPlaceholders($content)
    {
        $pattern = '/\{\{([^}]+)\}\}/';
        preg_match_all($pattern, $content, $matches);
        
        // Return unique placeholders
        return array_unique($matches[1]);
    }
    
    /**
     * Process a document for a specific school and event
     */
    public function processDocumentForSchool(TemplateDocument $template, School $school, ?SchoolEvent $event = null)
    {
        // Get placeholder values
        $placeholderValues = $this->getPlaceholderValues($school, $event);
        
        // Read template content
        $fullPath = Storage::disk('public')->path($template->file_path);
        $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
        
        // Generate new filename
        $newFilename = time() . '_' . $school->id . '_' . $template->id . '.' . $extension;
        $newPath = 'processed/' . $newFilename;
        
        // Process based on file type
        if (in_array($extension, ['doc', 'docx'])) {
            $processedPath = $this->processWordDocument($fullPath, $placeholderValues, $newPath);
        } else if ($extension === 'pdf') {
            $processedPath = $this->processPdfDocument($fullPath, $placeholderValues, $newPath);
        } else {
            // Simple text processing
            $content = Storage::disk('public')->get($template->file_path);
            $processedContent = $this->replacePlaceholders($content, $placeholderValues);
            Storage::disk('public')->put($newPath, $processedContent);
            $processedPath = $newPath;
        }
        
        // Create processed document record
        return ProcessedDocument::create([
            'template_document_id' => $template->id,
            'school_id' => $school->id,
            'school_event_id' => $event ? $event->id : null,
            'filename' => $newFilename,
            'file_path' => $processedPath,
        ]);
    }
    
    /**
     * Get all placeholder values combining school and event data
     */
    private function getPlaceholderValues(School $school, ?SchoolEvent $event = null)
    {
        $values = $school->getPlaceholderValues();
        
        if ($event) {
            $values = array_merge($values, $event->getPlaceholderValues());
        }
        
        return $values;
    }
    
    /**
     * Replace placeholders in content
     */
    private function replacePlaceholders($content, $values)
    {
        foreach ($values as $placeholder => $value) {
            $content = str_replace('{{' . $placeholder . '}}', $value, $content);
        }
        
        return $content;
    }
    
    /**
     * Process Word document and replace placeholders
     */
    private function processWordDocument($sourcePath, $placeholderValues, $destinationPath)
    {
        // Load the document
        $phpWord = WordIOFactory::load($sourcePath);
        
        // Replace placeholders in the document
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getText')) {
                    $text = $element->getText();
                    $newText = $this->replacePlaceholders($text, $placeholderValues);
                    $element->setText($newText);
                }
            }
        }
        
        // Save the document
        $newFile = Storage::disk('public')->path($destinationPath);
        $objWriter = WordIOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($newFile);
        
        return $destinationPath;
    }
    
    /**
     * Process PDF document and replace placeholders
     * Note: This is more complex in practice, might require PDF manipulation libraries
     */
    private function processPdfDocument($sourcePath, $placeholderValues, $destinationPath)
    {
        // In a real implementation, you might use a PDF manipulation library
        // For this example, we'll convert to Word, make changes, and convert back
        
        // For now, just copy the file (placeholder implementation)
        Storage::disk('public')->copy(
            str_replace('public/', '', $sourcePath), 
            $destinationPath
        );
        
        return $destinationPath;
    }
    
    /**
     * Export processed document to a specific format
     */
    public function exportDocument(ProcessedDocument $document, $format = 'pdf')
    {
        $sourcePath = Storage::disk('public')->path($document->file_path);
        $filename = pathinfo($document->filename, PATHINFO_FILENAME);
        $exportPath = 'exports/' . $filename . '.' . $format;
        
        if ($format === 'pdf' && !Str::endsWith($document->file_path, '.pdf')) {
            // Convert to PDF
            $phpWord = WordIOFactory::load($sourcePath);
            $pdfWriter = new PDFWriter($phpWord);
            $pdfWriter->save(Storage::disk('public')->path($exportPath));
        } else if ($format === 'docx' && !Str::endsWith($document->file_path, '.docx')) {
            // Convert to DOCX
            // Implementation depends on the source format
        } else {
            // Just copy the file if already in the right format
            Storage::disk('public')->copy(
                $document->file_path,
                $exportPath
            );
        }
        
        return $exportPath;
    }
    
    /**
     * Update edited content of a processed document
     */
    public function updateEditedContent(ProcessedDocument $document, $editedContent)
    {
        $document->update([
            'edited_content' => $editedContent
        ]);
        
        // Also update the file
        Storage::disk('public')->put($document->file_path, $editedContent);
        
        return $document;
    }
}
