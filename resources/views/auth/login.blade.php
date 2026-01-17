<x-layouts.app>
    <x-slot name="title">Giriş Yap - Öğrenci Takip Sistemi</x-slot>
    
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo -->
            <div class="text-center">
                <h2 class="text-4xl font-bold text-gray-800">
                    Öğrenci Takip Sistemi
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Hesabınıza giriş yapın
                </p>
            </div>

            <!-- Login Form -->
            <div class="card">
                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            E-posta Adresi
                        </label>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            autocomplete="email" 
                            required 
                            value="{{ old('email') }}"
                            class="input-field"
                            placeholder="ornek@email.com"
                        >
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Şifre
                        </label>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="current-password" 
                            required 
                            class="input-field"
                            placeholder="••••••••"
                        >
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input 
                                id="remember" 
                                name="remember" 
                                type="checkbox" 
                                class="h-4 w-4 text-accent-blue focus:ring-accent-blue border-gray-300 rounded"
                            >
                            <label for="remember" class="ml-2 block text-sm text-gray-700">
                                Beni hatırla
                            </label>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="btn-primary w-full">
                            Giriş Yap
                        </button>
                    </div>
                </form>

                <!-- Demo Accounts Info -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-xs text-gray-500 text-center mb-3">Demo Hesaplar:</p>
                    <div class="text-xs text-gray-600 space-y-1">
                        <p><strong>Admin:</strong> admin@ogrenci.com / password</p>
                        <p><strong>Koç:</strong> coach1@ogrenci.com / password</p>
                        <p><strong>Öğrenci:</strong> student1@ogrenci.com / password</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>

