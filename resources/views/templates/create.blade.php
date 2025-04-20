@extends('layouts.app')

@section('title', 'Upload Template')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Upload New Template</h1>
            <a href="{{ route('templates.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Templates
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Template Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('templates.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Template Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="document" class="form-label">Document File</label>
                        <div class="input-group">
                            <input type="file" class="form-control @error('document') is-invalid @enderror" id="document" name="document" required>
                            <span class="input-group-text"><i class="fas fa-file-upload"></i></span>
                        </div>
                        <div class="form-text">Supported formats: PDF, DOCX, DOC, TXT (Max: 10MB)</div>
                        @error('document')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Supported Placeholders</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item bg-transparent">School Name</li>
                                            <li class="list-group-item bg-transparent">School Address</li>
                                            <li class="list-group-item bg-transparent">School Affiliation No.</li>
                                            <li class="list-group-item bg-transparent">School Code</li>
                                            <li class="list-group-item bg-transparent">School Email</li>
                                            <li class="list-group-item bg-transparent">School Contact</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item bg-transparent">School Website</li>
                                            <li class="list-group-item bg-transparent">Principal Name</li>
                                            <li class="list-group-item bg-transparent">Event Date</li>
                                            <li class="list-group-item bg-transparent">Venue</li>
                                            <li class="list-group-item bg-transparent">Time</li>
                                            <li class="list-group-item bg-transparent">Subject</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload Template
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Add file input validation
    document.getElementById('document').addEventListener('change', function() {
        const fileSize = this.files[0].size / 1024 / 1024; // in MB
        const fileType = this.files[0].type;
        const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain'];
        
        if (fileSize > 10) {
            alert('File size exceeds 10MB. Please choose a smaller file.');
            this.value = '';
        } else if (!validTypes.includes(fileType) && 
                  !(this.files[0].name.endsWith('.doc') || 
                    this.files[0].name.endsWith('.docx') || 
                    this.files[0].name.endsWith('.pdf') || 
                    this.files[0].name.endsWith('.txt'))) {
            alert('Invalid file type. Please upload PDF, DOCX, DOC, or TXT files only.');
            this.value = '';
        }
    });
</script>
@endsection