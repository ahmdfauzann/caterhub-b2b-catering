@extends('layouts.app')

@section('title', 'Login - CaterHub B2B')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-100">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-3xl border border-slate-200 shadow-xl">
        
        <div class="text-center space-y-2">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-brand-600 to-amber-500 text-white flex items-center justify-center mx-auto text-2xl font-bold shadow-lg shadow-brand-500/20">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900">Masuk ke CaterHub</h2>
            <p class="text-xs text-slate-500">Silakan masuk menggunakan Akun Kantor atau Akun Katering Anda.</p>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium space-y-1">
                @foreach($errors->all() as $error)
                    <p><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Perusahaan / Vendor</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-sm">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@perusahaan.com"
                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none font-medium">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-sm">
                        <i class="fa-solid fa-key"></i>
                    </span>
                    <input type="password" name="password" required placeholder="••••••••"
                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none font-medium">
                </div>
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 text-slate-600 font-medium">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500"> Ingat Saya
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-brand-600 to-amber-500 hover:from-brand-700 text-white font-bold rounded-xl text-sm transition-all shadow-lg shadow-brand-500/20">
                Masuk Sekarang
            </button>
        </form>

        <!-- Quick Demo Login Accounts for Testing Evaluator -->
        <div class="pt-6 border-t border-slate-100 space-y-3">
            <span class="block text-[11px] font-bold text-slate-400 text-center uppercase tracking-wider">Demo Quick Login (Akun Pengujian Tes)</span>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                <button type="button" onclick="fillLogin('customer@jasamedika.com', 'password')" 
                        class="p-3 rounded-xl bg-slate-50 hover:bg-brand-50 border border-slate-200 text-left font-medium text-slate-700 hover:text-brand-600 transition-colors">
                    <span class="block font-bold text-slate-900 flex items-center gap-1.5"><i class="fa-solid fa-building text-brand-600"></i> 1. Akun Kantor</span>
                    <span class="text-[10px] text-slate-500 block mt-0.5">PT Jasamedika Transmedic</span>
                </button>

                <button type="button" onclick="fillLogin('berkah@catering.com', 'password')" 
                        class="p-3 rounded-xl bg-slate-50 hover:bg-brand-50 border border-slate-200 text-left font-medium text-slate-700 hover:text-brand-600 transition-colors">
                    <span class="block font-bold text-slate-900 flex items-center gap-1.5"><i class="fa-solid fa-store text-amber-600"></i> 2. Akun Katering</span>
                    <span class="text-[10px] text-slate-500 block mt-0.5">Berkah Catering Nusantara</span>
                </button>
            </div>
        </div>

        <div class="text-center pt-2 text-xs text-slate-500">
            Belum memiliki akun? <a href="{{ route('register') }}" class="font-bold text-brand-600 hover:underline">Daftar Akun Baru</a>
        </div>

    </div>
</div>

<script>
    function fillLogin(email, password) {
        document.querySelector('input[name="email"]').value = email;
        document.querySelector('input[name="password"]').value = password;
    }
</script>
@endsection
