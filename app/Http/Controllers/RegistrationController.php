<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
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
            $query->whereHas('event', function ($eventQuery) use ($titleFilter) {
                $eventQuery->where('title', 'like', '%' . $titleFilter . '%');
            });
        }
    
        if ($registrationId) {
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
        if (!$user->hasActiveEnrollment($event) && $event->hasAvailableSlots()) {
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
        try {
            $this->authorize('cancel', $registration);
        } catch (AuthorizationException $e) {
            return redirect()->route('registrations.index')
                ->with('error', 'Você não tem permissão para cancelar esta inscrição.');
        }

        if($registration->status == 'pendente'){
            $event = $registration->event;
            $registration->update(['status' => 'cancelada']);

            return redirect()->route('events.show', $event)
                ->with('success', 'Inscrição cancelada com sucesso.');
        }
        elseif($registration->status == 'processando pagamento'){
            $payment = $registration->payments();
            $payment->delete();
            $event = $registration->event;
            $registration->update(['status' => 'cancelada']);

            return redirect()->route('events.show', $event)
                ->with('success', 'Inscrição cancelada com sucesso.');
        }
        elseif($registration->status == 'pago'){
            return redirect()->route('refunds.askForRefund', $registration);
        }
        
    }

    public function listRegisters(Request $request, Event $event)
    {
        if (Gate::denies('list-registers', $event)) {
            return redirect()->route('dashboard')->with('error', 'Você não tem permissão para acessar estas inscrições.');
        }
        $nameFilter = $request->input('user_name');
        $statusFilter = $request->input('status_filter');

        $registrationsQuery = $event->registrations();

        if ($nameFilter) {
            $registrationsQuery->whereHas('user', function ($userQuery) use ($nameFilter) {
                $userQuery->where('name', 'like', '%' . $nameFilter . '%');
            });
        }
        if ($statusFilter) {
            $registrationsQuery->where('status', $statusFilter);
        }

        $registrations = $event->registrations;;
        
        $paidCount = $registrations->where('status', 'pago')->count();
        $pendingCount = $registrations->where('status', 'pendente')->count();
        $processingCount = $registrations->where('status', 'processando pagamento')->count();
        $waitingCount = $registrations->where('status', 'esperando por reembolso')->count();
        $cancelCount = $registrations->where('status', 'cancelada')->count();

        if ($registrationsQuery->count()){
            $registrations = $registrationsQuery->get();
        }

        $sucessoSessao = session('success');
        $erroSessao = session('error');

        if($sucessoSessao){
            return view('organizer.registrations', compact('event', 'registrations', 'paidCount', 'pendingCount', 'processingCount', 'waitingCount', 'cancelCount'))
                ->with('success', $sucessoSessao);
        }

        if($erroSessao){
            return view('organizer.registrations', compact('event', 'registrations', 'paidCount', 'pendingCount', 'processingCount', 'waitingCount', 'cancelCount'))
                ->with('success', $erroSessao);
        }

        return view('organizer.registrations', compact('event', 'registrations', 'paidCount', 'pendingCount', 'processingCount', 'waitingCount', 'cancelCount'));
    }
}
