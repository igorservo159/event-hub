<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $titleFilter = $request->input('title_filter');
        $dateFilter = $request->input('date_filter');
        $locationFilter = $request->input('location_filter');

        $query = Event::with('owner')->latest();

        if ($titleFilter) {
            $query->where('title', 'like', '%' . $titleFilter . '%');
        }

        if ($dateFilter) {
            $query->whereDate('data', $dateFilter);
        }

        if ($locationFilter) {
            $query->where('location', 'like', '%' . $locationFilter . '%');
        }

        $events = $query->get();

        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $this->authorize('create', Event::class);
        } catch (AuthorizationException $e) {
            return redirect()->route('events.index')->with('error', 'Você não tem permissão para criar eventos');
        }

        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $user = Auth::user();

        try {
            $this->authorize('create', Event::class);
        } catch (AuthorizationException $e) {
            return redirect()->route('events.index')->with('error', 'Você não tem permissão para criar eventos');
        }

        $attributes = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'data' => 'required|date',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        /** @var \App\Models\User $user */
        $event = $user->createdEvents()->create($attributes);

        return redirect()->route('events.index')->with('success', 'Evento criado com sucesso!');
    }

    

    /**
     * Display the specified resource.
     */
    public function show(Event $event): View
    {
        $sucessoSessao = session('success');
        $erroSessao = session('error');

        if($sucessoSessao){
            view('events.show', compact('event'))
                ->with('success', $sucessoSessao);
        }

        if($erroSessao){
            view('events.show', compact('event'))
                ->with('success', $erroSessao);
        }

        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        try {
            $this->authorize('update', $event);
        } catch (AuthorizationException $e) {
            return redirect()->route('events.myEvents')->with('error', 'Você não tem permissão para editar este evento');
        }

        $registrationsWithPayment = $event->registrations->whereIn('status', ['pago', 'processando pagamento'])->isNotEmpty();
        return view('events.edit', compact('event', 'registrationsWithPayment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        try {
            $this->authorize('update', $event);
        } catch (AuthorizationException $e) {
            return redirect()->route('events.myEvents')->with('error', 'Você não tem permissão para editar este evento');
        }
    
        $attributes = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'data' => 'required|date',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer',
            'price' => 'required|numeric',
        ]);
    
        $event->update($attributes);
    
        return redirect()->route('events.myEvents')
            ->with('success', 'Evento atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): RedirectResponse
    {
        try {
            $this->authorize('delete', $event);
        } catch (AuthorizationException $e) {
            return redirect()->route('events.myEvents')
                ->with('error', 'Você não tem permissão para excluir este evento');
        }

        $registrationsWithPayment = $event->registrations->whereIn('status', ['pago', 'processando pagamento', 'esperando por reembolso'])->isNotEmpty();
        
        if ($registrationsWithPayment) {
            return redirect()->route('events.myEvents')
                ->with('error', 'Já existem inscrições no evento com pagamento/reembolso em andamento');
        }

        $event->delete();

        return redirect()->route('events.myEvents')
            ->with('success', 'Evento excluído com sucesso!');
    }

    public function myEvents(Request $request)
    {
        try {
            $this->authorize('myEvents', Event::class);
        } catch (AuthorizationException $e) {
            return redirect()->route('events.index')
                ->with('error', 'Você não tem permissão para acessar os eventos do usuário');
        }
        
        $user = Auth::user();
        /** @var \App\Models\User $user */

        $titleFilter = $request->input('title_filter');
        $dateFilter = $request->input('date_filter');
        $locationFilter = $request->input('location_filter');

        $query = Event::where('owner_id', $user->id)->latest();

        if ($titleFilter) {
            $query->where('title', 'like', '%' . $titleFilter . '%');
        }

        if ($dateFilter) {
            $query->whereDate('data', $dateFilter);
        }

        if ($locationFilter) {
            $query->where('location', 'like', '%' . $locationFilter . '%');
        }

        $events = $query->get();

        return view('events.myEvents', compact('events'));
    }
}
