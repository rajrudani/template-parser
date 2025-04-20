<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Template Details
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">{{ $template->title }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-600 mb-2">File Information</h4>
                        <ul class="text-sm text-gray-700 space-y-1">
                            <li><strong>Filename:</strong> {{ $template->original_filename }}</li>
                            <li><strong>File Type:</strong>
                                {{ strtoupper(pathinfo($template->original_filename, PATHINFO_EXTENSION)) }}</li>
                            <li><strong>Uploaded:</strong> {{ $template->created_at->format('M d, Y H:i') }}</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-sm font-medium text-gray-600 mb-2">Detected Placeholders</h4>
                        <div class="flex flex-wrap gap-2">
                            @forelse($template->placeholder_mapping as $placeholder)
                                <span
                                    class="px-2 py-1 text-xs rounded bg-gray-100 border border-gray-300 text-gray-800">
                                    {{ $placeholder }}
                                </span>
                            @empty
                                <p class="text-gray-400 text-sm">No placeholders detected.</p>
                            @endforelse
                        </div>
                    </div>

                    @if ($template->description)
                        <div class="mb-4">
                            <h3 class="text-sm text-gray-600 font-medium">Description</h3>
                            <p class="text-gray-700">{{ $template->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-sm font-semibold text-gray-600 mb-4">Document Preview</h3>

                @php
                    $ext = strtolower(pathinfo($template->original_filename, PATHINFO_EXTENSION));
                @endphp

                @if (in_array($ext, ['pdf', 'doc', 'docx']))
                    <div class="text-center py-6">
                        <i class="fas fa-file-{{ $ext === 'pdf' ? 'pdf' : 'word' }} fa-5x text-indigo-500 mb-3"></i>
                        <p class="text-sm text-gray-600">
                            {{ strtoupper($ext) }} Document Uploaded
                        </p>
                    </div>
                @elseif($ext === 'txt')
                    <div
                        class="bg-gray-100 border rounded p-4 max-h-80 overflow-auto text-sm text-gray-700 whitespace-pre-wrap">
                        {{ Storage::disk('public')->get($template->file_path) }}
                    </div>
                @endif
            </div>

            <div class="bg-white shadow rounded-lg p-6 space-y-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-3">Standard Placeholders</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach (['School Name', 'School Address', 'School Affiliation No.', 'School Code', 'School Email', 'School Contact', 'School Website', 'Principal Name'] as $placeholder)
                            <span class="flex items-center gap-2 px-3 py-1 text-sm rounded-full 
                                         {{ in_array($placeholder, $template->placeholder_mapping) 
                                             ? 'bg-green-100 text-green-800' 
                                             : 'bg-gray-100 text-gray-500' }}">
                                <i class="fas {{ in_array($placeholder, $template->placeholder_mapping) 
                                                ? 'fa-check-circle' 
                                                : 'fa-times-circle' }}"></i>
                                {{ $placeholder }}
                            </span>
                        @endforeach
                    </div>
                </div>
            
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 mb-3">Event-Related Placeholders</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach (['Event Date', 'Venue', 'Time', 'Subject'] as $placeholder)
                            <span class="flex items-center gap-2 px-3 py-1 text-sm rounded-full 
                                         {{ in_array($placeholder, $template->placeholder_mapping) 
                                             ? 'bg-green-100 text-green-800' 
                                             : 'bg-gray-100 text-gray-500' }}">
                                <i class="fas {{ in_array($placeholder, $template->placeholder_mapping) 
                                                ? 'fa-check-circle' 
                                                : 'fa-times-circle' }}"></i>
                                {{ $placeholder }}
                            </span>
                        @endforeach
                    </div>
                </div>
            
                @if (Auth::user()->hasRole('super_admin'))
                    <form action="{{ route('templates.destroy', $template) }}" method="POST" class="pt-4 border-t">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-sm px-4 py-2 bg-red-600 text-white rounded shadow hover:bg-red-700 transition"
                            onclick="return confirm('Are you sure you want to remove this template?')">
                            <i class="fas fa-trash-alt mr-1"></i> Remove Template
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
