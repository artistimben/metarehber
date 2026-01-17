<div>
    <div class="mb-6">
        <a href="{{ route('coach.students') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Öğrencilere Dön
        </a>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <!-- Öğrenci Profil Kartı -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-start justify-between">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 h-20 w-20">
                    <div class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                        {{ substr($student->name, 0, 1) }}
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $student->name }}</h1>
                    <div class="space-y-1 text-gray-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ $student->email }}
                        </div>
                        @if($student->phone)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $student->phone }}
                            </div>
                        @endif
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Kayıt: {{ $student->created_at->format('d.m.Y') }}
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-2">
                <a href="{{ route('coach.assign', $student->id) }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Ders Ata
                </a>
            </div>
        </div>
    </div>

    <!-- İstatistik Kartları -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <!-- Konu İlerlemesi -->
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm text-gray-600">Konu İlerlemesi</div>
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $completionPercentage }}%</div>
            <div class="text-xs text-gray-500 mt-1">{{ $completedCount }}/{{ $totalAssignments }} konu</div>
            <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $completionPercentage }}%"></div>
            </div>
        </div>

        <!-- Çözülen Sorular -->
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm text-gray-600">Toplam Soru</div>
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ number_format($totalQuestions) }}</div>
            <div class="text-xs text-gray-500 mt-1">{{ number_format($correctAnswers) }} doğru</div>
        </div>

        <!-- Deneme Sayısı -->
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm text-gray-600">Deneme Sayısı</div>
                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $totalExams }}</div>
            <div class="text-xs text-gray-500 mt-1">Girilen deneme</div>
        </div>

        <!-- Ortalama Net -->
        <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="text-sm text-gray-600">Ortalama Net</div>
                <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ $avgNet ? number_format($avgNet, 1) : '0' }}</div>
            <div class="text-xs text-gray-500 mt-1">Net puan</div>
        </div>
    </div>

    <!-- Konu Takibi -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Konu Takibi ve İlerleme</h2>

        @if($assignments->isEmpty())
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <p class="text-gray-600 text-lg mb-2">Henüz konu atanmamış</p>
                <p class="text-gray-500 mb-4">Bu öğrenciye konu atamak için "Ders Ata" butonuna tıklayın</p>
                <a href="{{ route('coach.assign', $student->id) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Ders Ata
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($assignments as $fieldName => $fieldAssignments)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <!-- Alan Başlığı -->
                        <button 
                            wire:click="toggleField('{{ $fieldName }}')"
                            class="w-full bg-gradient-to-r from-blue-50 to-indigo-50 p-4 flex items-center justify-between hover:from-blue-100 hover:to-indigo-100 transition"
                        >
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 transition-transform {{ in_array($fieldName, $expandedFields) ? 'rotate-90' : '' }}" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-800">{{ $fieldName }}</h3>
                                @php
                                    $fieldTotal = $fieldAssignments->count();
                                    $fieldCompleted = $fieldAssignments->filter(fn($a) => $a->progress && $a->progress->is_completed)->count();
                                    $fieldPercentage = $fieldTotal > 0 ? round(($fieldCompleted / $fieldTotal) * 100, 1) : 0;
                                @endphp
                                <span class="text-sm text-gray-600">
                                    {{ $fieldCompleted }}/{{ $fieldTotal }} tamamlandı
                                </span>
                            </div>
                            <span class="text-sm font-medium text-blue-600">{{ $fieldPercentage }}%</span>
                        </button>

                        <!-- Dersler -->
                        @if(in_array($fieldName, $expandedFields))
                            <div class="p-4 bg-gray-50">
                                @php
                                    $groupedByCourse = $fieldAssignments->groupBy('course.name');
                                @endphp

                                @foreach($groupedByCourse as $courseName => $courseAssignments)
                                    <div class="mb-4 bg-white rounded-lg border border-gray-200 overflow-hidden">
                                        <!-- Ders Başlığı -->
                                        <button 
                                            wire:click="toggleCourse({{ $courseAssignments->first()->course->id }})"
                                            class="w-full p-3 flex items-center justify-between hover:bg-gray-50 transition"
                                        >
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 transition-transform {{ in_array($courseAssignments->first()->course->id, $expandedCourses) ? 'rotate-90' : '' }}" 
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                                </svg>
                                                <span class="font-medium text-gray-800">{{ $courseName }}</span>
                                                @php
                                                    $courseTotal = $courseAssignments->count();
                                                    $courseCompleted = $courseAssignments->filter(fn($a) => $a->progress && $a->progress->is_completed)->count();
                                                @endphp
                                                <span class="text-sm text-gray-500">({{ $courseCompleted }}/{{ $courseTotal }})</span>
                                            </div>
                                        </button>

                                        <!-- Konular -->
                                        @if(in_array($courseAssignments->first()->course->id, $expandedCourses))
                                            <div class="px-4 pb-4 space-y-2">
                                                @php
                                                    $groupedByTopic = $courseAssignments->groupBy('topic.name');
                                                @endphp

                                                @foreach($groupedByTopic as $topicName => $topicAssignments)
                                                    <div class="bg-gray-50 rounded-lg overflow-hidden border border-gray-200">
                                                        <button 
                                                            wire:click="toggleTopic({{ $topicAssignments->first()->topic->id }})"
                                                            class="w-full p-3 flex items-center justify-between hover:bg-gray-100 transition"
                                                        >
                                                            <div class="flex items-center gap-2">
                                                                <svg class="w-3 h-3 transition-transform {{ in_array($topicAssignments->first()->topic->id, $expandedTopics) ? 'rotate-90' : '' }}" 
                                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                                </svg>
                                                                <span class="font-medium text-gray-700">📖 {{ $topicName }}</span>
                                                                @php
                                                                    $topicCompleted = $topicAssignments->filter(fn($a) => $a->progress && $a->progress->is_completed)->count();
                                                                    $topicTotal = $topicAssignments->count();
                                                                @endphp
                                                                <span class="text-xs text-gray-500">({{ $topicCompleted }}/{{ $topicTotal }})</span>
                                                            </div>
                                                            @if($topicCompleted === $topicTotal)
                                                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                                </svg>
                                                            @endif
                                                        </button>

                                                        <!-- Alt Konular -->
                                                        @if(in_array($topicAssignments->first()->topic->id, $expandedTopics))
                                                            <div class="px-4 pb-4 space-y-2">
                                                                @foreach($topicAssignments as $assignment)
                                                                    <div class="bg-white rounded-lg border border-gray-200 p-3">
                                                                        <div class="flex items-start justify-between gap-2">
                                                                            <div class="flex items-start gap-2 flex-1">
                                                                                <input 
                                                                                    type="checkbox" 
                                                                                    wire:click="toggleProgress({{ $assignment->id }})"
                                                                                    {{ $assignment->progress && $assignment->progress->is_completed ? 'checked' : '' }}
                                                                                    class="mt-1 w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer"
                                                                                >
                                                                                <div class="flex-1">
                                                                                    <span class="font-medium text-gray-700 {{ $assignment->progress && $assignment->progress->is_completed ? 'line-through text-gray-400' : '' }}">
                                                                                        {{ $assignment->subTopic->name }}
                                                                                    </span>
                                                                                    @if($assignment->progress && $assignment->progress->completed_at)
                                                                                        <span class="text-xs text-gray-400 ml-2">
                                                                                            ✓ {{ $assignment->progress->completed_at->format('d.m.Y') }}
                                                                                        </span>
                                                                                    @endif
                                                                                    
                                                                                    <!-- Not Görüntüleme/Düzenleme -->
                                                                                    @if($editingNoteFor === $assignment->id)
                                                                                        <div class="mt-2">
                                                                                            <textarea 
                                                                                                wire:model="noteText"
                                                                                                rows="3"
                                                                                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                                                                placeholder="Konu hakkında not ekleyin..."
                                                                                            ></textarea>
                                                                                            <div class="flex gap-2 mt-2">
                                                                                                <button 
                                                                                                    wire:click="saveNote({{ $assignment->id }})"
                                                                                                    class="px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700"
                                                                                                >
                                                                                                    Kaydet
                                                                                                </button>
                                                                                                <button 
                                                                                                    wire:click="cancelNote"
                                                                                                    class="px-3 py-1 bg-gray-200 text-gray-700 text-sm rounded-lg hover:bg-gray-300"
                                                                                                >
                                                                                                    İptal
                                                                                                </button>
                                                                                            </div>
                                                                                        </div>
                                                                                    @else
                                                                                        @if($assignment->progress && $assignment->progress->notes)
                                                                                            <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded text-sm text-gray-700">
                                                                                                <div class="flex items-start justify-between gap-2">
                                                                                                    <div class="flex-1">
                                                                                                        <div class="font-medium text-yellow-800 mb-1">📝 Not:</div>
                                                                                                        {{ $assignment->progress->notes }}
                                                                                                    </div>
                                                                                                    <button 
                                                                                                        wire:click="startEditingNote({{ $assignment->id }})"
                                                                                                        class="text-blue-600 hover:text-blue-800 text-xs"
                                                                                                    >
                                                                                                        Düzenle
                                                                                                    </button>
                                                                                                </div>
                                                                                            </div>
                                                                                        @endif
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            @if(!$editingNoteFor || $editingNoteFor !== $assignment->id)
                                                                                <button 
                                                                                    wire:click="startEditingNote({{ $assignment->id }})"
                                                                                    class="text-gray-400 hover:text-blue-600 transition"
                                                                                    title="Not ekle"
                                                                                >
                                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                                                    </svg>
                                                                                </button>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

