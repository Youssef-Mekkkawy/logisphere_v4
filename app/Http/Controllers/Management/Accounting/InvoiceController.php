<?php

namespace App\Http\Controllers\Management\Accounting;

use App\Http\Controllers\Controller;
use App\Models\{Invoice, Company, Shipment};
use App\Http\Requests\Accounting\{StoreInvoiceRequest, UpdateInvoiceRequest};
use App\Services\{InvoiceService, PDFService};

class InvoiceController extends Controller
{
    protected $invoiceService;
    protected $pdfService;

    public function __construct(InvoiceService $invoiceService, PDFService $pdfService)
    {
        $this->invoiceService = $invoiceService;
        $this->pdfService = $pdfService;
    }

    public function index()
    {
        $invoices = Invoice::with(['company', 'shipment'])
            ->when(request('status'), fn($q, $status) => $q->where('status', $status))
            ->when(request('type'), fn($q, $type) => $q->where('type', $type))
            ->latest()
            ->paginate(20);

        return view('accounting.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $companies = Company::active()->get();
        $shipments = Shipment::active()->get();
        return view('accounting.invoices.create', compact('companies', 'shipments'));
    }

    public function store(StoreInvoiceRequest $request)
    {
        $invoice = $this->invoiceService->create($request->validated());
        return redirect()->route('invoices.index')
            ->with('success', 'Invoice created successfully!');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['company', 'shipment', 'payments']);
        return view('accounting.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $this->authorize('update', $invoice);

        $companies = Company::active()->get();
        $shipments = Shipment::active()->get();
        return view('accounting.invoices.edit', compact('invoice', 'companies', 'shipments'));
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $this->authorize('update', $invoice);

        $invoice = $this->invoiceService->update($invoice, $request->validated());
        return redirect()->route('accounting.invoices.index')
            ->with('success', 'Invoice updated successfully!');
    }

    public function destroy(Invoice $invoice)
    {
        $this->authorize('delete', $invoice);

        $this->invoiceService->delete($invoice);
        return redirect()->route('accounting.invoices.index')
            ->with('success', 'Invoice deleted successfully!');
    }

    public function generatePDF(Invoice $invoice)
    {
        return $this->pdfService->generateInvoicePDF($invoice);
    }

    public function sendToClient(Invoice $invoice)
    {
        $this->invoiceService->sendToClient($invoice);
        return back()->with('success', 'Invoice sent to client successfully!');
    }
}
