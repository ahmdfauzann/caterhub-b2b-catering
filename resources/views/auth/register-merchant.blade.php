@extends('layouts.app')

@section('title', 'Pendaftaran Mitra Katering - CaterHub')

@section('content')
<div class="py-12 bg-slate-100 min-h-[85vh] flex items-center justify-center">
    <div class="max-w-2xl w-full mx-auto px-4">
        <div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-xl space-y-6">
            
            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center mx-auto text-xl font-bold">
                    <i class="fa-solid fa-store"></i>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900">Formulir Pendaftaran Mitra Katering</h1>
                <p class="text-xs text-slate-500">Daftarkan usaha katering Anda untuk mulai menerima orderan B2B dari kantor.</p>
            </div>

            @if($errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium space-y-1">
                    @foreach($errors->all() as $error)
                        <p><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register.merchant') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Usaha Katering *</label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}" required placeholder="e.g. Berkah Catering Nusantara"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Penanggung Jawab / Owner *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Hj. Siti Rahmawati"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Vendor *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="catering@vendor.com"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">No. HP / WhatsApp Vendor *</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="081311223344"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kota Operasional *</label>
                        <select name="city" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
                            <option value="Jakarta Selatan">Jakarta Selatan</option>
                            <option value="Jakarta Pusat">Jakarta Pusat</option>
                            <option value="Tangerang Selatan">Tangerang Selatan</option>
                            <option value="Bandung">Bandung</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Spesialisasi Masakan *</label>
                        <input type="text" name="cuisine_type" value="{{ old('cuisine_type', 'Indonesian, Traditional') }}" required placeholder="e.g. Nusantara, Japanese, Healthy"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Dapur / Workshop Katering *</label>
                    <textarea name="address" rows="2" required placeholder="Jl. Raya No..., Kelurahan, Kecamatan..."
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">{{ old('address') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Singkat Usaha</label>
                    <textarea name="description" rows="2" placeholder="Jelaskan keunggulan & pengelaman dapur katering Anda..."
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi *</label>
                        <input type="password" name="password" required placeholder="Minimal 8 karakter"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Konfirmasi Kata Sandi *</label>
                        <input type="password" name="password_confirmation" required placeholder="Ulangi kata sandi"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-sm transition-all shadow-md">
                    Daftar Sebagai Mitra Katering
                </button>
            </form>

        </div>
    </div>
</div>
@endsection
