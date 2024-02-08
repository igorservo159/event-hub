<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\PermissionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $userData = $request->validated();

        // Verifica se o tipo de usuário foi alterado
        if ($userData['type'] !== $user->type) {
            if ($user->hasPendingPermissionRequest()) {
                return Redirect::route('dashboard')->with('error', 'Você já tem um pedido pendente. Não é possível alterar o tipo de usuário.');
            }
            // Cria o PermissionRequest apenas se o tipo for alterado para 'organizador' ou 'administrador'
            if ($user->type === 'inscrito' && ($userData['type'] === 'organizador' || $userData['type'] === 'administrador')) {
                PermissionRequest::create([
                    'user_id' => $user->id,
                    'requested_type' => $userData['type'],
                    'status' => 'pendente',
                ]);
            }

            elseif ($user->type === 'organizador' && $userData['type'] === 'administrador') {
                PermissionRequest::create([
                    'user_id' => $user->id,
                    'requested_type' => $userData['type'],
                    'status' => 'pendente',
                ]);
            }

            elseif ($user->type === 'organizador' && $userData['type'] === 'inscrito') {
                $user->type = $userData['type'];
            }
        }

        $userData['type'] = $user->type;

        $user->fill($userData);
        
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
