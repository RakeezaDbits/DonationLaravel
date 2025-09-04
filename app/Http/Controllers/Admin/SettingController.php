<?php
// app/Http/Controllers/Admin/SettingController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::all();
        return view('admin.settings.index', compact('paymentMethods'));
    }

    public function updatePaymentMethod(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'details' => 'required|array',
            'is_active' => 'boolean'
        ]);

        $paymentMethod->update([
            'details' => $request->details,
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Payment method updated successfully');
    }
}