<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        $users = User::query()
            ->orderBy('name')
            ->get();

        return view('admin.users.index', [
            'roles' => $this->roles(),
            'statuses' => $this->statuses(),
            'users' => $users,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $user->update($request->safe()->only(['role', 'status']));

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Usuario actualizado correctamente.');
    }

    /**
     * @return array<string, string>
     */
    private function roles(): array
    {
        return [
            User::ROLE_ADMIN => 'Administrador',
            User::ROLE_EDITOR => 'Editor',
            User::ROLE_VIEWER => 'Viewer',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function statuses(): array
    {
        return [
            User::STATUS_PENDING => 'Pendiente',
            User::STATUS_ACTIVE => 'Activo',
            User::STATUS_DISABLED => 'Deshabilitado',
        ];
    }
}
