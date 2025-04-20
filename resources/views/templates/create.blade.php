<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Create New Template
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-900 shadow-md rounded-xl overflow-hidden">
            <div class="px-6 py-5">
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
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm @error('document') border-red-500 @enderror"
                            required>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Supported formats: PDF, DOCX, DOC, TXT
                            (Max: 10MB)</p>
                        @error('document')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div
                        class="bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-4 rounded-md">
                        <h4 class="font-semibold text-gray-700 dark:text-gray-200 mb-3">Supported Placeholders</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-400">
                            <ul class="space-y-1">
                                <li>School Name</li>
                                <li>School Address</li>
                                <li>School Affiliation No.</li>
                                <li>School Code</li>
                                <li>School Email</li>
                                <li>School Contact</li>
                            </ul>
                            <ul class="space-y-1">
                                <li>School Website</li>
                                <li>Principal Name</li>
                                <li>Event Date</li>
                                <li>Venue</li>
                                <li>Time</li>
                                <li>Subject</li>
                            </ul>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            <i class="fas fa-upload mr-2"></i> Upload Template
                        </button>
                    </div>
                </form>
            </div>
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
