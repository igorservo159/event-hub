<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

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

    public function reembolso(Registration $registration)
    {
        $payment = $registration->payments();
        if($registration->status == 'processando pagamento'){
            $payment->delete();
            $registration->update(['status' => 'pendente']);
            return redirect()->route('registrations.destroy', $registration);
        }
        elseif($registration->status == 'pago'){
            $diasRestantes = Carbon::parse($registration->event->data)->diffInDays(Carbon::now());
            if($diasRestantes >= 15){
                $payment->delete();
                $registration->update(['status' => 'pendente']);
                return redirect()->route('registrations.destroy', $registration);
            }
            else{
                return redirect()->route('registrations.index')
                    ->with('error', 'Já passou do prazo de reembolso');
            }
        }
    }
}
