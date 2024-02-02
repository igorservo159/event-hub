<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function index(Request $request): View
    {
        $titleFilter = $request->input('title_filter');
        $registrationId = $request->input('registration_id');

        $user = Auth::user();

        /** @var \App\Models\User $user */
        $query = $user->registrations()
            ->with('event')
            ->latest();

        if ($titleFilter && !$registrationId) {
            // Apenas aplicar o filtro de título se não houver filtro de registrationId
            $query->whereHas('event', function ($eventQuery) use ($titleFilter) {
                $eventQuery->where('title', 'like', '%' . $titleFilter . '%');
            });
        }
    
        if ($registrationId) {
            // Se houver um filtro de registrationId, obter o nome do evento correspondente
            $eventTitle = Registration::find($registrationId)->event->title ?? '';
            $titleFilter = $eventTitle;
            $query->where('id', $registrationId);
        }

        $registrations = $query->get();

        return view('registrations.index', compact('registrations', 'titleFilter'));
    }
    
    public function store(Event $event): RedirectResponse
    {
        $user = Auth::user();

        /** @var \App\Models\User $user */
        if (!$user->isEnrolled($event) && $event->hasAvailableSlots()) {
            $event->registrations()->create(['user_id' => $user->id]);

            return redirect()->route('events.show', $event)
                ->with('success', 'Inscrição realizada com sucesso!');
        } else {
            return redirect()->route('events.index', $event)
                ->with('error', 'Não foi possível realizar a inscrição.');
        }
    }

    public function destroy(Registration $registration): RedirectResponse
    {
        if (Auth::user()->id === $registration->user_id) {
            if($registration->status == 'pendente'){
                $event = $registration->event;
                $registration->delete();

                return redirect()->route('events.show', $event)
                    ->with('success', 'Inscrição removida com sucesso.');
            }
            elseif($registration->status == 'processando pagamento' || $registration->status == 'pago'){
                return redirect()->route('payments.reembolso', $registration);
            }
        } else {
            return redirect()->route('registrations.index')
                ->with('error', 'Você não tem permissão para remover esta inscrição.');
        }
    }

    public function registeredsList(Event $event)
    {
        if (Auth::user()->id !== $event->owner->id) {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para acessar estas inscrições.');
        }

        $registrations = $event->registrations;
        $registration = $event->registrations->first();

        if($registration->event == null){
            return view('dashboard');
        }

        dd($registration->event->toArray());
        $paidCount = $registrations->where('status', 'pago')->count();
        $pendingCount = $registrations->where('status', 'pendente')->count();
        $processingCount = $registrations->where('status', 'processando pagamento')->count();

        return view('organizer.registrations', compact('event', 'registrations', 'paidCount', 'pendingCount', 'processingCount'));
    
    }

    public function approvePayment(Registration $registration): RedirectResponse
    {
        
        if (Auth::user()->id !== $registration->event->owner->id) {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para aprovar o pagamento desta inscrição.');
        }

        if ($registration->status !== 'processando pagamento') {
            return redirect()->route('registrations.index')
                ->with('error', 'Esta inscrição não está aguardando pagamento.');
        }

        $registration->update(['status' => 'pago']);

        return redirect()->route('registrations.index')
            ->with('success', 'Pagamento aprovado com sucesso!');
    }
}
