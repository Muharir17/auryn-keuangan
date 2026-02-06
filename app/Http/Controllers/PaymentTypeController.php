<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    public function index()
    {
        $paymentTypes = PaymentType::orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10);

        return view('payment-types.index', compact('paymentTypes'));
    }

    public function create()
    {
        return view('payment-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:payment_types,code|max:50',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'default_amount' => 'required|numeric|min:0',
            'frequency' => 'required|in:monthly,yearly,once,custom',
            'is_mandatory' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_mandatory'] = $request->has('is_mandatory');
        $validated['is_active'] = $request->has('is_active');

        PaymentType::create($validated);

        return redirect()->route('payment-types.index')
            ->with('success', 'Jenis pembayaran berhasil ditambahkan.');
    }

    public function edit(PaymentType $paymentType)
    {
        return view('payment-types.edit', compact('paymentType'));
    }

    public function update(Request $request, PaymentType $paymentType)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:payment_types,code,' . $paymentType->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'default_amount' => 'required|numeric|min:0',
            'frequency' => 'required|in:monthly,yearly,once,custom',
            'is_mandatory' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_mandatory'] = $request->has('is_mandatory');
        $validated['is_active'] = $request->has('is_active');

        $paymentType->update($validated);

        return redirect()->route('payment-types.index')
            ->with('success', 'Jenis pembayaran berhasil diperbarui.');
    }

    public function destroy(PaymentType $paymentType)
    {
        if ($paymentType->bills()->count() > 0) {
            return redirect()->route('payment-types.index')
                ->with('error', 'Tidak dapat menghapus jenis pembayaran yang sudah digunakan.');
        }

        $paymentType->delete();

        return redirect()->route('payment-types.index')
            ->with('success', 'Jenis pembayaran berhasil dihapus.');
    }
}
