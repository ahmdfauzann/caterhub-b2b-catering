@extends('layouts.merchant')

@section('title', 'Tambah Menu Makanan - Merchant Portal')
@section('page-title', 'Tambah Menu Baru')

@section('content')
<div class="max-w-2xl space-y-6">
    
    <a href="{{ route('merchant.menus.index') }}" class="text-xs text-brand-600 font-bold hover:underline inline-block mb-2">
        <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Daftar Menu
    </a>

    <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm">
        
        @if($errors->any())
            <div class="p-4 mb-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium space-y-1">
                @foreach($errors->all() as $error)
                    <p><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('merchant.menus.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Menu Makanan *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Paket Nasi Liwet Komplit"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none font-medium">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori Menu *</label>
                    <select name="category_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none font-medium">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Harga per Porsi (Rp) *</label>
                    <input type="number" name="price" value="{{ old('price') }}" required placeholder="e.g. 38000"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none font-medium">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Minimal Porsi Pesanan *</label>
                    <input type="number" name="min_portion" value="{{ old('min_portion', 10) }}" required placeholder="e.g. 10"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tag Diet / Label (Opsional)</label>
                    <input type="text" name="dietary_tags" value="{{ old('dietary_tags') }}" placeholder="e.g. Halal, Non-MSG, Low Calorie"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none font-medium">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Lengkap & Lauk Pauk *</label>
                <textarea name="description" rows="3" required placeholder="Jelaskan rincian isi menu, lauk pauk, dan penyajian..."
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none font-medium">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Foto Menu Makanan (PNG/JPG/WEBP)</label>
                <input type="file" name="photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer">
                    <input type="checkbox" name="is_available" value="1" checked class="rounded text-brand-600 focus:ring-brand-500">
                    Menu Siap Dipesan (Status Aktif)
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl text-xs shadow-md transition-all">
                Simpan Menu Makanan
            </button>
        </form>

    </div>
</div>
@endsection
