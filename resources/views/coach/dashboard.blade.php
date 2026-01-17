<x-layouts.coach>
    <x-slot name="title">Dashboard</x-slot>
    
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Öğrencilerim</h1>
            <button class="btn-primary">
                + Öğrenci Ekle
            </button>
        </div>
        
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <div class="card">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-accent-blue bg-opacity-10 rounded-lg p-3">
                        <svg class="h-6 w-6 text-accent-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <dt class="text-sm font-medium text-gray-500 truncate">Toplam Öğrenci</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ auth()->user()->students->count() ?? 0 }}</dd>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Öğrencilerim</h3>
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">İsim</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">E-posta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kayıt Tarihi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach(auth()->user()->students as $student)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $student->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student->created_at->format('d.m.Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.coach>

