@extends('layouts.app')

@section('title', 'Templates')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h1>Document Templates</h1>
        {{-- @if(Auth::user()->hasRole('super_admin')) --}}
            <a href="{{ route('templates.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Upload New Template
            </a>
        {{-- @endif --}}
    </div>
</div>

<div class="row">
    @forelse($templates as $template)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 template-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $template->title }}</h5>
                    <span class="badge bg-primary">{{ pathinfo($template->filename, PATHINFO_EXTENSION) }}</span>
                </div>
                <div class="card-body">
                    @if($template->description)
                        <p class="card-text">{{ $template->description }}</p>
                    @endif
                    
                    <div class="mb-3">
                        <small class="text-muted">Placeholders:</small>
                        <div class="d-flex flex-wrap mt-2">
                            @foreach($template->placeholder_mapping as $placeholder)
                                <span class="badge bg-light text-dark placeholder-badge">
                                    {{ $placeholder }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('templates.show', $template) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        
                        {{-- @if(Auth::user()->hasRole('principal')) --}}
                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#processModal{{ $template->id }}">
                                <i class="fas fa-magic"></i> Process Document
                            </button>
                            
                            <!-- Process Modal -->
                            <div class="modal fade" id="processModal{{ $template->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Process Template: {{ $template->title }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('templates.preview', $template) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                {{-- <input type="hidden" name="school_id" value="{{ Auth::user()->school_id }}"> --}}
                                                <input type="hidden" name="school_id" value="1">
                                                
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
                        
                        {{-- @if(Auth::user()->hasRole('super_admin')) --}}
                            <form action="{{ route('templates.destroy', $template) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to remove this template?')">
                                    <i class="fas fa-trash-alt"></i> Remove
                                </button>
                            </form>
                        {{-- @endif --}}
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> No templates available.
                {{-- @if(Auth::user()->hasRole('super_admin')) --}}
                    <a href="{{ route('templates.create') }}" class="alert-link">Upload a new template</a>.
                {{-- @endif --}}
            </div>
        </div>
    @endforelse
</div>
@endsection