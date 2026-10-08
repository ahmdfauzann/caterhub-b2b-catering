@extends('layouts.app')

@section('title', 'Pilih Jenis Pendaftaran - CaterHub')

@section('content')
<div class="py-16 bg-slate-100 min-h-[80vh] flex items-center justify-center">
    <div class="max-w-4xl w-full mx-auto px-4 space-y-8">
        
        <div class="text-center space-y-2">
            <h1 class="text-3xl font-extrabold text-slate-900">Bergabung dengan CaterHub</h1>
            <p class="text-sm text-slate-500">Pilih jenis akun yang ingin Anda daftarkan di platform B2B Catering Marketplace.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Register as Customer (Kantor) -->
            <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-lg hover:shadow-2xl transition-all duration-300 flex flex-col justify-between space-y-6 group">
                <div class="space-y-4">
                    <div class="w-16 h-16 rounded-2xl bg-brand-100 text-brand-600 flex items-center justify-center text-3xl font-bold group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <h2 class="text-2xl font-extrabold text-slate-900">Daftar Akun Kantor / Perusahaan</h2>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Untuk tim HRD, Procurement, atau General Affairs kantor yang butuh pasokan katering harian, meeting, dan event perusahaan secara teratur.
                    </p>
                    <ul class="text-xs text-slate-500 space-y-2 pt-2">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-500"></i> Invoice B2B Otomatis & Terstruktur</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-500"></i> Pilihan Puluhan Vendor Verified</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-500"></i> Penjadwalan Makan Siang Fleksibel</li>
                    </ul>
                </div>

                <a href="{{ route('register.customer') }}" class="w-full py-3.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-center text-xs rounded-xl shadow-md transition-all">
                    Daftar Sebagai Kantor <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>

            <!-- Register as Merchant (Vendor Katering) -->
            <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-lg hover:shadow-2xl transition-all duration-300 flex flex-col justify-between space-y-6 group">
                <div class="space-y-4">
                    <div class="w-16 h-16 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-3xl font-bold group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-store"></i>
                    </div>
                    <h2 class="text-2xl font-extrabold text-slate-900">Daftar Sebagai Mitra Katering</h2>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Untuk pengusaha katering, dapur B2B, dan penyedia jasa boga yang ingin menjangkau ratusan klien kantor dan perusahaan di kota Anda.
                    </p>
                    <ul class="text-xs text-slate-500 space-y-2 pt-2">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-500"></i> Dasbor Pengelolaan Menu & Pesanan</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-500"></i> Jangkauan Klien Corporate Besar</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-500"></i> Laporan Penjualan & Tagihan Real-time</li>
                    </ul>
                </div>

                <a href="{{ route('register.merchant') }}" class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-center text-xs rounded-xl shadow-md transition-all">
                    Daftar Sebagai Mitra Katering <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
