<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\{Payment, Invoice};
use App\Http\Requests\Accounting\StorePaymentRequest;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        $payments = Payment::with(['invoice', 'company'])
            ->when(request('method'), fn($q, $method) => $q->where('method', $method))
            ->when(request('type'), fn($q, $type) => $q->where('type', $type))
            ->latest()
            ->paginate(20);

        return view('dashboard.accounting.payments.index', compact('payments'));
    }

    public function create()
    {
        $unpaidInvoices = Invoice::unpaid()->with('company')->get();
        return view('dashboard.accounting.payments.create', compact('unpaidInvoices'));
    }

    public function store(StorePaymentRequest $request)
    {
        $payment = $this->paymentService->record($request->validated());
        return redirect()->route('accounting.payments.index')
            ->with('success', 'Payment recorded successfully!');
    }

    public function show(Payment $payment)
    {
        $payment->load(['invoice', 'company']);
        return view('dashboard.accounting.payments.show', compact('payment'));
    }

    public function destroy(Payment $payment)
    {
        $this->authorize('delete', $payment);

        $this->paymentService->delete($payment);
        return redirect()->route('accounting.payments.index')
            ->with('success', 'Payment deleted successfully!');
    }
}
