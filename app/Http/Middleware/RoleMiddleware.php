<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        $userRole = auth()->user()->role;

        if (!in_array($userRole, $roles)) {
            if ($userRole === 'merchant' && in_array('customer', $roles)) {
                return redirect()->route('customer.cart')->with('error', 'Anda saat ini masuk sebagai Mitra Katering (Merchant). Fitur checkout & pembelian B2B khusus diperuntukkan bagi Akun Kantor/Perusahaan (Customer). Silakan masuk menggunakan Akun Kantor (contoh: customer@jasamedika.com) untuk menyelesaikan pemesanan.');
            }

            if ($userRole === 'customer' && in_array('merchant', $roles)) {
                return redirect()->route('customer.dashboard')->with('error', 'Anda saat ini masuk sebagai Akun Kantor (Customer). Portal Merchant khusus diperuntukkan bagi Mitra Katering (Vendor).');
            }

            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
