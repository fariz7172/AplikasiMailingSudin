@extends('layouts.admin')

@section('title', 'Pengaturan Akun')
@section('page_title', 'Profil & Keamanan Akun')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto" x-data="{
    showCurrentPass: false,
    showNewPass: false,
    showConfirmPass: false,
    showDeleteModal: false
}">
    <!-- Header Banner Profil -->
    <div class="bg-gradient-to-r from-primary via-indigo-700 to-accent rounded-3xl p-6 md:p-8 text-white shadow-xl shadow-primary/20 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
            <div class="relative">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=FFFFFF&color=2F2FE4&size=128&bold=true"
                    alt="{{ $user->name }}"
                    class="w-24 h-24 rounded-2xl shadow-lg border-4 border-white/20 object-cover">
                <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-emerald-500 rounded-full border-2 border-white flex items-center justify-center" title="Online">
                    <span class="w-2 h-2 bg-white rounded-full"></span>
                </div>
            </div>
            <div class="flex-1 space-y-1">
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-2">
                    <h2 class="text-2xl font-black tracking-tight">{{ $user->name }}</h2>
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-md text-white text-xs font-black uppercase rounded-full tracking-wider">
                        {{ $user->role ?? 'Administrator' }}
                    </span>
                </div>
                <p class="text-white/80 text-sm flex items-center justify-center md:justify-start gap-1.5">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                    {{ $user->email }}
                </p>
                <p class="text-white/60 text-xs flex items-center justify-center md:justify-start gap-1.5 pt-1">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    Bergabung sejak {{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Alert Status Notifikasi -->
    @if (session('status') === 'profile-updated')
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in"
         x-data="{ show: true }" x-show="show" x-transition>
        <div class="w-8 h-8 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
        </div>
        <div class="flex-1">
            <p class="font-bold text-sm">Berhasil Disimpan!</p>
            <p class="text-xs text-emerald-600">Informasi profil Anda telah berhasil diperbarui.</p>
        </div>
        <button @click="show = false" class="text-emerald-400 hover:text-emerald-700 p-1">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>
    @endif

    @if (session('status') === 'password-updated')
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in"
         x-data="{ show: true }" x-show="show" x-transition>
        <div class="w-8 h-8 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
            <i data-lucide="shield-check" class="w-5 h-5 text-emerald-600"></i>
        </div>
        <div class="flex-1">
            <p class="font-bold text-sm">Password Berhasil Diubah!</p>
            <p class="text-xs text-emerald-600">Kata sandi akun Anda telah diperbarui dengan aman.</p>
        </div>
        <button @click="show = false" class="text-emerald-400 hover:text-emerald-700 p-1">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Card 1: Informasi Profil -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="bg-slate-50 px-8 py-6 border-b border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-primary/10 text-primary rounded-2xl flex items-center justify-center shadow-sm">
                    <i data-lucide="user" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-800">Informasi Profil</h3>
                    <p class="text-xs text-slate-400">Perbarui nama dan alamat email akun Anda</p>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" class="p-8 space-y-6 flex-1 flex flex-col justify-between">
                @csrf
                @method('patch')

                <div class="space-y-5">
                    <div>
                        <label class="form-label-premium">Nama Lengkap</label>
                        <div class="relative">
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="form-input-premium pl-11 @error('name') border-rose-500 @enderror"
                                placeholder="Nama Anda">
                            <i data-lucide="user" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                        </div>
                        @error('name')
                            <p class="text-rose-500 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label-premium">Alamat Email</label>
                        <div class="relative">
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="form-input-premium pl-11 @error('email') border-rose-500 @enderror"
                                placeholder="email@contoh.com">
                            <i data-lucide="mail" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                        </div>
                        @error('email')
                            <p class="text-rose-500 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label-premium">Role / Hak Akses</label>
                        <div class="relative">
                            <input type="text" value="{{ ucfirst($user->role ?? 'Administrator') }}" disabled
                                class="form-input-premium pl-11 bg-slate-100 text-slate-500 cursor-not-allowed border-slate-200">
                            <i data-lucide="shield" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1 italic">* Hak akses akun diatur oleh Superadmin.</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                    <button type="submit"
                        class="px-6 py-3 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/30 hover:bg-indigo-700 transition-all flex items-center gap-2 text-sm">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Simpan Profil
                    </button>
                </div>
            </form>
        </div>

        <!-- Card 2: Ganti Password -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="bg-slate-50 px-8 py-6 border-b border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center shadow-sm">
                    <i data-lucide="key-round" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-800">Ganti Password</h3>
                    <p class="text-xs text-slate-400">Pastikan gunakan kata sandi yang aman dan kuat</p>
                </div>
            </div>

            <form method="POST" action="{{ route('password.update') }}" class="p-8 space-y-5 flex-1 flex flex-col justify-between">
                @csrf
                @method('put')

                <div class="space-y-4">
                    <!-- Current Password -->
                    <div>
                        <label class="form-label-premium">Password Saat Ini</label>
                        <div class="relative">
                            <input :type="showCurrentPass ? 'text' : 'password'" name="current_password" required
                                class="form-input-premium pl-11 pr-11 @if($errors->updatePassword->has('current_password')) border-rose-500 @endif"
                                placeholder="Masukkan password lama">
                            <i data-lucide="lock" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                            <button type="button" @click="showCurrentPass = !showCurrentPass"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none">
                                <i data-lucide="eye" x-show="!showCurrentPass" class="w-4 h-4"></i>
                                <i data-lucide="eye-off" x-show="showCurrentPass" class="w-4 h-4" x-cloak></i>
                            </button>
                        </div>
                        @if ($errors->updatePassword->has('current_password'))
                            <p class="text-rose-500 text-xs mt-1.5 font-semibold">{{ $errors->updatePassword->first('current_password') }}</p>
                        @endif
                    </div>

                    <!-- New Password -->
                    <div>
                        <label class="form-label-premium">Password Baru</label>
                        <div class="relative">
                            <input :type="showNewPass ? 'text' : 'password'" name="password" required
                                class="form-input-premium pl-11 pr-11 @if($errors->updatePassword->has('password')) border-rose-500 @endif"
                                placeholder="Minimal 8 karakter">
                            <i data-lucide="key" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                            <button type="button" @click="showNewPass = !showNewPass"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none">
                                <i data-lucide="eye" x-show="!showNewPass" class="w-4 h-4"></i>
                                <i data-lucide="eye-off" x-show="showNewPass" class="w-4 h-4" x-cloak></i>
                            </button>
                        </div>
                        @if ($errors->updatePassword->has('password'))
                            <p class="text-rose-500 text-xs mt-1.5 font-semibold">{{ $errors->updatePassword->first('password') }}</p>
                        @endif
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="form-label-premium">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <input :type="showConfirmPass ? 'text' : 'password'" name="password_confirmation" required
                                class="form-input-premium pl-11 pr-11 @if($errors->updatePassword->has('password_confirmation')) border-rose-500 @endif"
                                placeholder="Ulangi password baru">
                            <i data-lucide="check" class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                            <button type="button" @click="showConfirmPass = !showConfirmPass"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none">
                                <i data-lucide="eye" x-show="!showConfirmPass" class="w-4 h-4"></i>
                                <i data-lucide="eye-off" x-show="showConfirmPass" class="w-4 h-4" x-cloak></i>
                            </button>
                        </div>
                        @if ($errors->updatePassword->has('password_confirmation'))
                            <p class="text-rose-500 text-xs mt-1.5 font-semibold">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                        @endif
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                    <button type="submit"
                        class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-2xl shadow-lg shadow-amber-500/20 transition-all flex items-center gap-2 text-sm">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                        Perbarui Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
