<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Refund;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class RefundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $titleFilter = $request->input('title_filter');

        $user = Auth::user();

        /** @var \App\Models\User $user */
        $query = $user->refunds()
            ->with('payment.registration.event')
            ->latest();

        if ($titleFilter) {
            $query->whereHas('payment.registration.event', function ($eventQuery) use ($titleFilter) {
                $eventQuery->where('title', 'like', '%' . $titleFilter . '%');
            });
        }

        $refunds = $query->get();

        return view('refunds.index', compact('refunds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Registration $registration)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        if ($user->id !== $registration->user->id) {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão de pedir reembolso para esta inscrição.');
        }

        $existingRefund = $user->refunds()
            ->whereHas('payment.registration', function ($query) use ($registration) {
                $query->where('registrations.id', $registration->id);
            })
            ->where('decisao', 'pendente')
            ->first();

        if ($existingRefund) {
            return redirect()->route('registrations.index')
                ->with('error', 'Já há um pedido de reembolso pendente para esta inscrição.');
        }
        
        $validatedData = $request->validate([
            'value' => 'required|numeric',
            'reason' => 'required|in:evento_errado,imprevisto,nao_gostou,outro',
            'explanation' => 'required|string',
            'payment_id' => 'required|exists:payments,id',
        ]);

        $refund = new Refund([
            'value' => $validatedData['value'],
            'reason' => $validatedData['reason'],
            'explanation' => $validatedData['explanation'],
            'payment_id' => $validatedData['payment_id'],
            'user_id' => $user->id,
        ]);

        $refund->save();
        $registration->update(['status' => 'esperando por reembolso']);

        return redirect()->route('registrations.index')->with('success', 'Pedido de reembolso enviado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Refund $refund)
    {
        return view('refunds.show', compact('refund'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Refund $refund)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Refund $refund)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Refund $refund)
    {
        //
    }

    public function listRefunds(Request $request, Event $event)
    {
        if (Auth::user()->id !== $event->owner->id) {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para acessar estas inscrições.');
        }

        $titleFilter = $request->input('title_filter');

        $registrations = $event->registrations;

        $refunds = collect();

        foreach ($registrations as $registration) {
            $refunds = $refunds->merge($registration->payments->flatMap->refunds);
        }

        if ($titleFilter) {
            $refunds = $refunds->filter(function ($refund) use ($titleFilter) {
                return str_contains(strtolower($refund->payment->registration->event->title), strtolower($titleFilter));
            });
        }

        return view('organizer.refunds', compact('event', 'refunds'));
    }

    public function askForRefund(Registration $registration)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        if ($user->id !== $registration->user->id) {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão de pedir reembolso para esta inscrição.');
        }
        if($registration->status == 'pago'){
            $diasRestantes = Carbon::parse($registration->event->data)->diffInDays(Carbon::now());
            if($diasRestantes >= 15){
                $payment = $registration->payments()->where('status', 'finalizado')->first();
                if($payment){
                    return view('refunds.create', compact('registration', 'payment'));
                }
                else{
                    return redirect()->route('registrations.index')
                        ->with('error', 'Não foi encontrado um pagamento finalizado para esta inscrição.');
                }
            }
            else{
                return redirect()->route('registrations.index')
                    ->with('error', 'Já passou do prazo de reembolso');
            }
        }
    }

    public function denyRefund(Refund $refund)
    {
        // Adicione aqui a lógica necessária para negar o reembolso, por exemplo, alterar o status para "negada".
        $refund->update(['decisao' => 'negada']);
        $refund->payment->registration->update(['status' => 'pago']);

        return Redirect::back()->with('success', 'Pedido de reembolso negado com sucesso!');
    }

    /**
     * Approve the specified refund.
     */
    public function approveRefund(Refund $refund)
    {
        $refund->update(['decisao' => 'aprovada']);
        $refund->payment->registration->update(['status' => 'cancelada']);

        return Redirect::back()->with('success', 'Pedido de reembolso aprovado com sucesso!');
    }
}
