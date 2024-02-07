<?php

namespace App\Http\Controllers;

use App\Models\PermissionRequest;
use Illuminate\Support\Facades\Auth;

class PermissionRequestController extends Controller
{
    /**
     * Exibe os pedidos de permissão (apenas para administradores).
     */
    public function index()
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        if (!$user->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Você não tem permissão para acessar esta página.');
        }

        $permissionRequests = PermissionRequest::where('status', 'pendente')->get();

        $sucessoSessao = session('success');
        $erroSessao = session('error');

        if($sucessoSessao){
            return view('admin.list-permission-requests', compact('permissionRequests'))
                ->with('success', $sucessoSessao);
        }

        if($erroSessao){
            return view('admin.list-permission-requests', compact('permissionRequests'))
                ->with('error', $erroSessao);
        }

        return view('admin.list-permission-requests', compact('permissionRequests'));
    }

    /**
     * Aprova um pedido de permissão (apenas para administradores).
     */
    public function approve(PermissionRequest $permissionrequest)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        if (!$user->isAdmin()) {
            return redirect()->route('permissionRequests.index')->with('error', 'Você não tem permissão realizar esta ação.');
        }

        $permissionrequest->update(['status' => 'aprovado']);
        $permissionrequest->user()->update(['type' => $permissionrequest->requested_type]);

        return redirect()->route('permissionRequests.index')
            ->with('success', 'Pedido aprovado!');
    }

    /**
     * Nega um pedido de permissão (apenas para administradores).
     */
    public function deny(PermissionRequest $permissionrequest)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        if (!$user->isAdmin()) {
            return redirect()->route('permissionRequests.index')->with('error', 'Você não tem permissão realizar esta ação.');
        }

        $permissionrequest->update(['status' => 'negado']);

        return redirect()->route('permissionRequests.index')
            ->with('success', 'Pedido negado!');    
    }
}
