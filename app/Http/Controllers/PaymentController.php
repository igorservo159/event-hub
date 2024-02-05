<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $titleFilter = $request->input('title_filter');

        $user = Auth::user();

        /** @var \App\Models\User $user */
        $query = $user->payments()
            ->with('registration.event')
            ->latest();

        if ($titleFilter) {
            $query->whereHas('registration.event', function ($eventQuery) use ($titleFilter) {
                $eventQuery->where('title', 'like', '%' . $titleFilter . '%');
            });
        }

        $payments = $query->get();

        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Registration $registration)
    {
        if (Auth::user()->id !== $registration->user_id) {
            return redirect()->route('registrations.index')
                ->with('error', 'Você não tem permissão para realizar o pagamento desta inscrição.');
        }

        $eventPrice = $registration->event->price;

        return view('payments.create', compact('registration', 'eventPrice'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Registration $registration): RedirectResponse
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'value' => 'required|numeric',
            'method' => 'required|in:pix,credito,debito,boleto',
            'registration_id' => 'required|exists:registrations,id',
        ]);

        $payment = new Payment($validatedData);

        $payment = new Payment([
            'value' => $validatedData['value'],
            'method' => $validatedData['method'],
            'registration_id' => $validatedData['registration_id'],
            'user_id' => $user->id,
        ]);

        $payment->save();

        $registration->update(['status' => 'processando pagamento']);

        return redirect()->route('payments.index')
            ->with('success', 'Pagamento realizado com sucesso!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Pagamento excluído com sucesso!');
    }

    public function approvePayment(Registration $registration): RedirectResponse
    {
        $event = $registration->event;

        if (Auth::user()->id !== $registration->event->owner->id) {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para aprovar o pagamento desta inscrição.');
        }

        if ($registration->status !== 'processando pagamento') {
            return redirect()->route('registrations.listRegisters', $event)
                ->with('error', 'Esta inscrição não está aguardando pagamento.');
        }
        
        $payment = $registration->payments()->where('status', 'processando')->first();
        if ($payment) {
            $payment->update(['status' => 'finalizado']);
            $registration->update(['status' => 'pago']);
        }
        return redirect()->route('registrations.listRegisters', $event)
            ->with('success', 'Pagamento aprovado com sucesso!');
    }

    
}
