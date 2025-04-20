@extends('layouts.app')

@section('title', 'My Documents')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1>My Documents</h1>
            <a href="{{ route('templates.index') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Process New Document
            </a>
        </div>
    </div>
</div>

<div class="row">
    @forelse($documents as $document)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 template-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $document->templateDocument->title }}</h5>
                    <span class="badge bg-primary">{{ pathinfo($document->filename, PATHINFO_EXTENSION) }}</span>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-calendar-alt text-secondary me-2"></i>
                        <span>Created: {{ $document->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    
                    @if($document->schoolEvent)
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-star text-warning me-2"></i>
                            <span>Event: {{ $document->schoolEvent->event_name }}</span>
                        </div>
                    @endif
                    
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file-alt text-primary me-2"></i>
                        <span>File: {{ $document->filename }}</span>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('processed.edit', $document) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('processed.export', ['document' => $document, 'format' => 'pdf']) }}">
                                        <i class="fas fa-file-pdf me-2"></i> PDF
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('processed.export', ['document' => $document, 'format' => 'docx']) }}">
                                        <i class="fas fa-file-word me-2"></i> DOCX
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> You don't have any processed documents yet.
                <a href="{{ route('templates.index') }}" class="alert-link">Process a new document</a> from available templates.
            </div>
        </div>
    @endforelse
</div>
@endsection