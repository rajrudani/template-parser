<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl
                text-gray-800 dark:text-gray-200 leading-tight">
                Create Template
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-900 shadow-md rounded-xl overflow-hidden">
            <form action="{{ route('templates.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Template
                        Title</label>
                    <input type="text" name="title" id="title"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm @error('title') border-red-500 @enderror"
                        value="{{ old('title') }}" required>
                    @error('title')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description
                        (Optional)</label>
                    <textarea name="description" id="description" rows="3"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="document"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Document File</label>
                    <input type="file" name="document" id="document"
                        class="mt-1 block w-full border-gray-300 py-3 px-3 dark:bg-gray-800 dark:text-white rounded-md shadow-sm @error('document') border-red-500 @enderror"
                        required>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Supported formats: PDF, DOCX, DOC, TXT (Max: 10MB)</p>
                    @error('document')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-5 rounded-md">
                    <h4 class="font-semibold text-gray-700 dark:text-gray-200 mb-4">Supported Placeholders</h4>
                    <div class="flex flex-wrap gap-3">
                        @foreach([
                            'School Name', 'School Address', 'School Affiliation No.', 'School Code', 'School Email',
                            'School Contact', 'School Website', 'Principal Name', 'Event Date', 'Venue', 'Time', 'Subject'
                        ] as $placeholder)
                            <span class="bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 px-3 py-1 rounded-full text-sm shadow-sm">
                                {{ $placeholder }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <div>
                    <button type="submit"
                        class="inline-flex items-center px-5 py-3 bg-blue-600 text-white text-sm font-semibold rounded-md shadow">
                        <i class="fas fa-upload mr-2"></i> Upload Template
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('document').addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;

            const fileSize = file.size / 1024 / 1024;
            const validTypes = ['application/pdf', 'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain'
            ];

            if (fileSize > 10) {
                alert('File size exceeds 10MB. Please choose a smaller file.');
                this.value = '';
            } else if (!validTypes.includes(file.type) &&
                !['.doc', '.docx', '.pdf', '.txt'].some(ext => file.name.endsWith(ext))) {
                alert('Invalid file type. Please upload PDF, DOCX, DOC, or TXT files only.');
                this.value = '';
            }
        });
    </script>
</x-app-layout>
