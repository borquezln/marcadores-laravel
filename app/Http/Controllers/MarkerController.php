<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMarkerRequest;
use App\Http\Requests\UpdateMarkerRequest;
use App\Models\Marker;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MarkerController extends Controller
{
    public function index(Request $request): View
    {
        $markers = Marker::query()
            ->with('user')
            ->latest()
            ->get();

        return view('markers.index', [
            'markers' => $markers,
            'statuses' => $this->statuses(),
            'types' => $this->types(),
        ]);
    }

    public function create(): View
    {
        return view('markers.create', [
            'marker' => new Marker([
                'type' => Marker::TYPE_PLACE,
                'status' => Marker::STATUS_ACTIVE,
            ]),
            'types' => $this->types(),
            'statuses' => $this->statuses(),
        ]);
    }

    public function store(StoreMarkerRequest $request): RedirectResponse
    {
        $marker = new Marker($request->validated());
        $marker->user()->associate($request->user());
        $marker->save();

        return redirect()
            ->route('markers.index')
            ->with('status', 'Marcador creado correctamente.');
    }

    public function edit(Marker $marker, Request $request): View
    {
        $this->ensureCanManage($request->user(), $marker);

        return view('markers.edit', [
            'marker' => $marker,
            'types' => $this->types(),
            'statuses' => $this->statuses(),
        ]);
    }

    public function update(UpdateMarkerRequest $request, Marker $marker): RedirectResponse
    {
        $this->ensureCanManage($request->user(), $marker);

        $marker->update($request->validated());

        return redirect()
            ->route('markers.index')
            ->with('status', 'Marcador actualizado correctamente.');
    }

    public function destroy(Request $request, Marker $marker): RedirectResponse
    {
        $this->ensureCanManage($request->user(), $marker);

        $marker->update([
            'status' => Marker::STATUS_REMOVED,
        ]);

        return redirect()
            ->route('markers.index')
            ->with('status', 'Marcador marcado como removido.');
    }

    /**
     * @return array<string, string>
     */
    private function types(): array
    {
        return [
            Marker::TYPE_PLACE => 'Lugar',
            Marker::TYPE_BILLBOARD => 'Cartel',
            Marker::TYPE_STAND => 'Puesto',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function statuses(): array
    {
        return [
            Marker::STATUS_ACTIVE => 'Activo',
            Marker::STATUS_INACTIVE => 'Inactivo',
            Marker::STATUS_REMOVED => 'Removido',
        ];
    }

    private function ensureCanManage(User $user, Marker $marker): void
    {
        if ($user->isAdmin()) {
            return;
        }

        abort_if($marker->user_id !== $user->id, 403);
    }
}
