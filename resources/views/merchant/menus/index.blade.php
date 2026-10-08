@extends('layouts.merchant')

@section('title', 'Kelola Menu Makanan - Merchant Portal')
@section('page-title', 'Daftar Menu Makanan')

@section('content')
<div class="space-y-6">
    
    <!-- Filter & Add Button Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <form action="{{ route('merchant.menus.index') }}" method="GET" class="flex items-center gap-3 flex-1">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama menu..."
                   class="px-4 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-500 outline-none w-full sm:w-64">
            
            <select name="category_id" class="px-4 py-2 rounded-xl border border-slate-300 text-xs focus:border-brand-500 outline-none">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-brand-600 transition-colors">
                Filter
            </button>
        </form>

        <a href="{{ route('merchant.menus.create') }}" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah Menu Baru
        </a>
    </div>

    <!-- Menus Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        @if($menus->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="p-4">Foto & Menu</th>
                            <th class="p-4">Kategori</th>
                            <th class="p-4">Harga / Porsi</th>
                            <th class="p-4">Min. Porsi</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium">
                        @foreach($menus as $menu)
                            <tr class="hover:bg-slate-50">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $menu->photo_url }}" alt="{{ $menu->name }}" class="w-12 h-12 rounded-xl object-cover border">
                                        <div>
                                            <span class="font-extrabold text-slate-900 block text-sm">{{ $menu->name }}</span>
                                            <span class="text-[10px] text-slate-400 block">{{ Str::limit($menu->description, 40) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-slate-700 font-bold">
                                    {{ $menu->category ? $menu->category->name : '-' }}
                                </td>
                                <td class="p-4 font-extrabold text-brand-600">
                                    Rp {{ number_format($menu->price, 0, ',', '.') }}
                                </td>
                                <td class="p-4 font-bold text-slate-800">
                                    {{ $menu->min_portion }} porsi
                                </td>
                                <td class="p-4">
                                    @if($menu->is_available)
                                        <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Tersedia</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold">Habis / Non-Aktif</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right space-x-2">
                                    <a href="{{ route('merchant.menus.edit', $menu->id) }}" class="px-3 py-1.5 bg-amber-500 text-slate-900 rounded-lg text-xs font-bold hover:bg-amber-400 transition-colors">
                                        Edit
                                    </a>
                                    <form action="{{ route('merchant.menus.destroy', $menu->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-rose-600 text-white rounded-lg text-xs font-bold hover:bg-rose-700 transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $menus->links() }}
            </div>
        @else
            <div class="p-12 text-center text-xs text-slate-400 space-y-3">
                <i class="fa-solid fa-utensils text-3xl text-slate-300"></i>
                <p>Belum ada menu makanan. Klik tombol di atas untuk menambah menu baru.</p>
            </div>
        @endif
    </div>

</div>
@endsection
