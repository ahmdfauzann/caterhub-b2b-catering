<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show($invoiceNumber)
    {
        // Decode URI if needed and query by invoice_number
        $invoice = Invoice::with(['order.customer.customerProfile', 'order.merchant', 'order.items'])
            ->where('invoice_number', urldecode($invoiceNumber))
            ->orWhere('invoice_number', $invoiceNumber)
            ->firstOrFail();

        return view('invoice.show', compact('invoice'));
    }

    public function print($invoiceNumber)
    {
        $invoice = Invoice::with(['order.customer.customerProfile', 'order.merchant', 'order.items'])
            ->where('invoice_number', urldecode($invoiceNumber))
            ->orWhere('invoice_number', $invoiceNumber)
            ->firstOrFail();

        return view('invoice.show', compact('invoice'));
    }
}
