<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Deneme Takibi</h2>
            <p class="text-sm text-gray-600 mt-1">Deneme sınavı sonuçlarınızı kaydedin</p>
        </div>
        <button wire:click="openModal" class="btn-primary">
            + Deneme Ekle
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="card bg-blue-50">
            <div class="text-3xl font-bold text-blue-600">{{ $stats['total_exams'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Toplam Deneme</div>
        </div>
        <div class="card bg-green-50">
            <div class="text-3xl font-bold text-green-600">{{ number_format($stats['avg_net'] ?? 0, 2) }}</div>
            <div class="text-sm text-gray-600 mt-1">Ortalama Net</div>
        </div>
        <div class="card bg-purple-50">
            <div class="text-3xl font-bold text-purple-600">{{ number_format($stats['best_net'] ?? 0, 2) }}</div>
            <div class="text-sm text-gray-600 mt-1">En Yüksek Net</div>
        </div>
        <div class="card bg-red-50">
            <div class="text-3xl font-bold text-red-600">{{ number_format($stats['worst_net'] ?? 0, 2) }}</div>
            <div class="text-sm text-gray-600 mt-1">En Düşük Net</div>
        </div>
    </div>

    <!-- Field Stats -->
    @if(count($fieldStats) > 0)
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Alan Bazlı İstatistikler</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($fieldStats as $fieldName => $stats)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm font-medium text-gray-700 mb-2">{{ $fieldName }}</div>
                        <div class="text-2xl font-bold text-blue-600">{{ number_format($stats['avg_net'], 2) }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $stats['count'] }} deneme</div>
                        <div class="text-xs text-gray-500">En iyi: {{ number_format($stats['best_net'], 2) }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Exams Table -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tarih</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deneme Adı</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tür</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ders</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doğru</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Yanlış</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Boş</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Net</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">İşlem</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($examResults as $result)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $result->exam_date->format('d.m.Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $result->exam_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $result->exam_type ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $result->field?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $result->course?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                {{ $result->correct_answers }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                {{ $result->wrong_answers }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $result->blank_answers }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">
                                {{ number_format($result->net_score, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button 
                                    wire:click="delete({{ $result->id }})"
                                    onclick="return confirm('Bu kaydı silmek istediğinize emin misiniz?')"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    Sil
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-4 text-center text-gray-500">
                                Henüz deneme kaydı bulunmamaktadır.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t">
            {{ $examResults->links() }}
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeModal">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white" wire:click.stop>
                <div class="flex items-center justify-between pb-4 border-b">
                    <h3 class="text-xl font-semibold text-gray-900">Deneme Sonucu Ekle</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deneme Adı *</label>
                        <input type="text" wire:model="exam_name" class="input-field" placeholder="TYT Deneme 1">
                        @error('exam_name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tarih *</label>
                        <input type="date" wire:model="exam_date" class="input-field">
                        @error('exam_date') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alan/Branş</label>
                            <select wire:model.live="field_id" class="input-field">
                                <option value="">Alan Seçin</option>
                                @foreach($fields as $field)
                                    <option value="{{ $field->id }}">{{ $field->name }}</option>
                                @endforeach
                            </select>
                            @error('field_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sınav Tipi</label>
                            <select wire:model="exam_type" class="input-field">
                                <option value="">Tür Seçin</option>
                                @foreach($examTypes as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                            @error('exam_type') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ders</label>
                        <select wire:model="course_id" class="input-field">
                            <option value="">Ders Seçin</option>
                            @if($field_id && count($filteredCourses) > 0)
                                @foreach($filteredCourses as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                @endforeach
                            @else
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('course_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Doğru *</label>
                            <input type="number" wire:model.live="correct_answers" class="input-field" placeholder="30" min="0">
                            @error('correct_answers') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Yanlış *</label>
                            <input type="number" wire:model.live="wrong_answers" class="input-field" placeholder="8" min="0">
                            @error('wrong_answers') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Boş *</label>
                            <input type="number" wire:model="blank_answers" class="input-field" placeholder="2" min="0">
                            @error('blank_answers') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="text-sm text-gray-700 mb-1">Hesaplanan Net:</div>
                        <div class="text-3xl font-bold text-blue-600">
                            {{ number_format($net_score, 2) }}
                        </div>
                        <div class="text-xs text-gray-500 mt-1">Net = Doğru - (Yanlış ÷ 4)</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notlar</label>
                        <textarea wire:model="notes" class="input-field" rows="3" placeholder="İsteğe bağlı notlar..."></textarea>
                        @error('notes') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                        <button type="button" wire:click="closeModal" class="btn-secondary">İptal</button>
                        <button type="submit" class="btn-primary">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
