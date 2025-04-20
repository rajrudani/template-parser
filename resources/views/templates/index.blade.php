<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl
                text-gray-800 dark:text-gray-200 leading-tight">
                Document Templates
            </h2>
            @if (Auth::user()->hasRole('super_admin'))
                <a href="{{ route('templates.create') }}"
                    class="inline-flex items-center px-5 py-3 bg-blue-600 text-white text-sm font-semibold rounded-md shadow">
                    <i class="fas fa-plus mr-2"></i> Upload New Template
                </a>
            @endif
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($templates as $template)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md flex flex-col h-full">
                    <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                        <h5 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $template->title }}</h5>
                    </div>

                    <div class="p-4 flex-1">
                        @if ($template->description)
                            <small class="text-gray-500 dark:text-gray-400 font-medium">Description:</small>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">{{ $template->description }}</p>
                        @endif

                        <div>
                            <small class="text-gray-500 dark:text-gray-400 font-medium">Placeholders:</small>
                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach ($template->placeholder_mapping as $placeholder)
                                    <span
                                        class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs px-2 py-1 rounded">
                                        {{ $placeholder }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div
                        class="p-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center space-x-2">
                        <a href="{{ route('templates.show', $template) }}"
                            class="text-sm px-3 py-1.5 rounded-lg text-blue-600 bg-yellow-500 hover:bg-white-100 transition">
                            <i class="fas fa-eye mr-1"></i> View Details
                        </a>

                        @if (Auth::user()->hasRole('principal'))
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = true"
                                    class="text-sm px-3 py-1.5 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                                    <i class="fas fa-magic mr-1"></i> Process Document
                                </button>

                                <!-- Modal -->
                                <div x-show="open" x-cloak
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
                                    <div @click.away="open = false"
                                        class="bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-md w-full p-6">
                                        <div class="flex justify-between items-center mb-4">
                                            <h5 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                                Process Template: {{ $template->title }}
                                            </h5>
                                            <button @click="open = false"
                                                class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-xl">
                                                &times;
                                            </button>
                                        </div>

                                        <form action="{{ route('templates.preview', $template) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="school_id" value="1" />

                                            <div class="mb-4">
                                                <label for="event_id"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                    Select Event (Optional)
                                                </label>
                                                <select name="event_id" id="event_id"
                                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm">
                                                    <option value="">No Event</option>
                                                    {{-- @foreach (Auth::user()->school->events as $event)
                                                    <option value="{{ $event->id }}">{{ $event->event_name }} ({{ $event->event_date->format('d/m/Y') }})</option>
                                                @endforeach --}}
                                                </select>
                                            </div>

                                            <div class="flex justify-end gap-2">
                                                <button type="submit"
                                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                                    Process Document
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (Auth::user()->hasRole('super_admin'))
                            <form action="{{ route('templates.destroy', $template) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to remove this template?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm px-3 py-1.5 bg-red-600 rounded-lg transition">
                                    <i class="fas fa-trash-alt mr-1"></i> Remove
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-3">
                    <div class="bg-blue-50 dark:bg-gray-800 text-blue-700 dark:text-blue-300 p-4 rounded-lg text-sm">
                        <i class="fas fa-info-circle mr-1"></i> No templates available.
                        @if (Auth::user()->hasRole('super_admin'))
                            <a href="{{ route('templates.create') }}"
                                class="underline hover:text-blue-900 dark:hover:text-blue-400 ml-1">Upload a new
                                template</a>.
                        @endif
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
