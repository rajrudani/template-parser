@extends('layouts.app')

@section('title', 'Template Details')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Template Details</h1>
            <a href="{{ route('templates.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Templates
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">{{ $template->title }}</h5>
            </div>
            <div class="card-body">
                @if($template->description)
                    <div class="mb-3">
                        <h6>Description:</h6>
                        <p>{{ $template->description }}</p>
                    </div>
                    <hr>
                @endif
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6>File Information:</h6>
                        <dl class="row">
                            <dt class="col-sm-4">Filename:</dt>
                            <dd class="col-sm-8">{{ $template->original_filename }}</dd>
                            
                            <dt class="col-sm-4">File Type:</dt>
                            <dd class="col-sm-8">{{ strtoupper(pathinfo($template->original_filename, PATHINFO_EXTENSION)) }}</dd>
                            
                            <dt class="col-sm-4">Uploaded:</dt>
                            <dd class="col-sm-8">{{ $template->created_at->format('M d, Y H:i') }}</dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <h6>Placeholders Detected:</h6>
                        <div class="d-flex flex-wrap mt-2">
                            @forelse($template->placeholder_mapping as $placeholder)
                                <span class="badge bg-light text-dark placeholder-badge">
                                    {{ $placeholder }}
                                </span>
                            @empty
                                <span class="text-muted">No placeholders detected</span>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Preview</h6>
                    </div>
                    <div class="card-body">
                        @if(in_array(pathinfo($template->original_filename, PATHINFO_EXTENSION), ['pdf', 'docx', 'doc']))
                            <div class="text-center">
                                <i class="fas fa-file{{ pathinfo($template->original_filename, PATHINFO_EXTENSION) === 'pdf' ? '-pdf' : '-word' }} fa-5x text-primary mb-3"></i>
                                <p>{{ pathinfo($template->original_filename, PATHINFO_EXTENSION) === 'pdf' ? 'PDF' : 'Word' }} document</p>
                            </div>
                        @elseif(pathinfo($template->original_filename, PATHINFO_EXTENSION) === 'txt')
                            <div class="preview-frame">
                                <pre>{{ Storage::disk('public')->get($template->file_path) }}</pre>
                            </div>
                        @endif
                    </div>
                </div>
                
                {{-- @if(Auth::user()->hasRole('principal')) --}}
                    <div class="d-grid">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#processModal">
                            <i class="fas fa-magic"></i> Process Template for My School
                        </button>
                    </div>
                    
                    <!-- Process Modal -->
                    <div class="modal fade" id="processModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Process Template: {{ $template->title }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('templates.preview', $template) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <input type="hidden" name="school_id" value="1">
                                        {{-- <input type="hidden" name="school_id" value="{{ Auth::user()->school_id }}"> --}}
                                        
                                        <div class="mb-3">
                                            <label for="event_id" class="form-label">Select Event (Optional)</label>
                                            <select class="form-select" name="event_id" id="event_id">
                                                <option value="">No Event</option>
                                                {{-- @foreach(Auth::user()->school->events as $event)
                                                    <option value="{{ $event->id }}">{{ $event->event_name }} ({{ $event->event_date->format('d/m/Y') }})</option>
                                                @endforeach --}}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Process Document</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                {{-- @endif --}}
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Placeholder Validation</h5>
            </div>
            <div class="card-body">
                <h6>Standard Placeholders</h6>
                <ul class="list-group mb-3">
                    @foreach(['School Name', 'School Address', 'School Affiliation No.', 'School Code', 'School Email', 'School Contact', 'School Website', 'Principal Name'] as $standardPlaceholder)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $standardPlaceholder }}
                            @if(in_array($standardPlaceholder, $template->placeholder_mapping))
                                <span class="badge bg-success rounded-pill"><i class="fas fa-check"></i></span>
                            @else
                                <span class="badge bg-secondary rounded-pill"><i class="fas fa-times"></i></span>
                            @endif
                        </li>
                    @endforeach
                </ul>
                
                <h6>Event-related Placeholders</h6>
                <ul class="list-group">
                    @foreach(['Event Date', 'Venue', 'Time', 'Subject'] as $eventPlaceholder)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $eventPlaceholder }}
                            @if(in_array($eventPlaceholder, $template->placeholder_mapping))
                                <span class="badge bg-success rounded-pill"><i class="fas fa-check"></i></span>
                            @else
                                <span class="badge bg-secondary rounded-pill"><i class="fas fa-times"></i></span>
                            @endif
                        </li>
                    @endforeach
                </ul>
                
                {{-- @if(Auth::user()->hasRole('super_admin')) --}}
                    <div class="mt-4">
                        <form action="{{ route('templates.destroy', $template) }}" method="POST" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to remove this template?')">
                                <i class="fas fa-trash-alt"></i> Remove Template
                            </button>
                        </form>
                    </div>
                {{-- @endif --}}
            </div>
        </div>
    </div>
</div>
@endsection