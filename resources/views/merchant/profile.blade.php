@extends('layouts.merchant')

@section('title', 'Profil Katering - Merchant Portal')
@section('page-title', 'Pengelolaan Profil Katering')

@section('content')
<div class="max-w-4xl space-y-6">
    
    <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('merchant.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Usaha Katering *</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $merchant->company_name) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">No. HP / WhatsApp Vendor *</label>
                    <input type="text" name="phone" value="{{ old('phone', $merchant->phone) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none font-medium">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kota Operasional *</label>
                    <input type="text" name="city" value="{{ old('city', $merchant->city) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Spesialisasi Masakan *</label>
                    <input type="text" name="cuisine_type" value="{{ old('cuisine_type', $merchant->cuisine_type) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Min. Order Amount (Rp)</label>
                    <input type="number" name="min_order_amount" value="{{ old('min_order_amount', $merchant->min_order_amount) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none font-medium">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Dapur / Workshop *</label>
                <textarea name="address" rows="3" required
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none font-medium">{{ old('address', $merchant->address) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Deskripsi Usaha Katering</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none font-medium">{{ old('description', $merchant->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Upload Logo Dapur (PNG/JPG)</label>
                    <input type="file" name="logo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Upload Banner Sampul (PNG/JPG)</label>
                    <input type="file" name="banner" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                </div>
            </div>

            <button type="submit" class="px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md transition-all">
                Simpan Profil Katering
            </button>
        </form>
    </div>

</div>
@endsection
