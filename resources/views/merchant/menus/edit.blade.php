@extends('layouts.merchant')

@section('title', 'Edit Menu Makanan - Merchant Portal')
@section('page-title', 'Edit Menu Makanan')

@section('content')
<div class="max-w-2xl space-y-6">
    
    <a href="{{ route('merchant.menus.index') }}" class="text-xs text-brand-600 font-bold hover:underline inline-block mb-2">
        <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Daftar Menu
    </a>

    <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm">
        
        <form action="{{ route('merchant.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Menu Makanan *</label>
                <input type="text" name="name" value="{{ old('name', $menu->name) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none font-medium">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kategori Menu *</label>
                    <select name="category_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none font-medium">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $menu->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Harga per Porsi (Rp) *</label>
                    <input type="number" name="price" value="{{ old('price', $menu->price) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none font-medium">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Minimal Porsi Pesanan *</label>
                    <input type="number" name="min_portion" value="{{ old('min_portion', $menu->min_portion) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tag Diet / Label</label>
                    <input type="text" name="dietary_tags" value="{{ old('dietary_tags', $menu->dietary_tags) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none font-medium">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Lengkap *</label>
                <textarea name="description" rows="3" required
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none font-medium">{{ old('description', $menu->description) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Foto Menu (Biarkan kosong jika tidak diubah)</label>
                <input type="file" name="photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer">
                    <input type="checkbox" name="is_available" value="1" {{ $menu->is_available ? 'checked' : '' }} class="rounded text-brand-600 focus:ring-brand-500">
                    Menu Siap Dipesan (Status Aktif)
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl text-xs shadow-md transition-all">
                Update Menu Makanan
            </button>
        </form>

    </div>
</div>
@endsection
