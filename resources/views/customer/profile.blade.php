@extends('layouts.app')

@section('title', 'Profil Perusahaan - CaterHub')

@section('content')
<div class="py-10 bg-slate-100 min-h-[85vh]">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">Profil Kantor / Perusahaan</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola rincian informasi instansi dan alamat utama pengiriman makan siang.</p>
        </div>

        <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm">
            <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Perusahaan *</label>
                        <input type="text" name="company_name" value="{{ old('company_name', $customer->company_name ?? '') }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama PIC Penanggung Jawab *</label>
                        <input type="text" name="pic_name" value="{{ old('pic_name', $customer->pic_name ?? auth()->user()->name) }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">No. Telepon / WhatsApp *</label>
                        <input type="text" name="phone" value="{{ old('phone', $customer->phone ?? auth()->user()->phone) }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kota Operasional Kantor *</label>
                        <input type="text" name="city" value="{{ old('city', $customer->city ?? 'Jakarta') }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jumlah Karyawan Kantor</label>
                    <input type="number" name="employee_count" value="{{ old('employee_count', $customer->employee_count ?? 50) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Lengkap Pengiriman *</label>
                    <textarea name="office_address" rows="3" required
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 outline-none">{{ old('office_address', $customer->office_address ?? '') }}</textarea>
                </div>

                <button type="submit" class="px-6 py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md transition-all">
                    Simpan Perubahan Profil
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
