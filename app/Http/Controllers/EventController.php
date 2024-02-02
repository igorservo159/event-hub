<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

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
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isOrganizador()) {
            return view('events.create');
        } else {
            return redirect()->route('events.index')
                ->with('error', 'Você não tem permissão para criar eventos');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        /** @var \App\Models\User $user */
        if ($user->isOrganizador()) {
            $attributes = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'data' => 'required|date',
                'location' => 'required|string|max:255',
                'capacity' => 'required|integer',
                'price' => 'required|numeric',
            ]);

            $event = $user->createdEvents()->create($attributes);

            return redirect()->route('events.index')
                ->with('success', 'Evento criado com sucesso!');
        } else {
            return redirect()->route('events.index')
                ->with('error', 'Você não tem permissão para criar eventos');
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Event $event): View
    {
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event): View
    {
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $attributes = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'data' => 'required|date',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $event->update($attributes);

        return redirect()->route('events.index')
            ->with('success', 'Evento atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): RedirectResponse
    {
        $user = Auth::user();

        /** @var \App\Models\User $user */
        if ($user->isOrganizador() && $user->id === $event->owner->id) {
            $event->delete();
    
            return redirect()->route('events.index')
                ->with('success', 'Evento excluído com sucesso!');
        } else {
            return redirect()->route('events.index')
                ->with('error', 'Você não tem permissão para excluir este evento');
        }
    }

    public function myEvents()
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        if ($user->isOrganizador()) {

            $events = $user->createdEvents;
            
            return view('events.myEvents', compact('events'));
        } else {
            return redirect()->route('events.index')
                ->with('error', 'Você não tem permissão para acessar os eventos do usuário');
        }
    }
}
