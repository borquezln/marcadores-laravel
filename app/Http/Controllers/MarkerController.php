<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMarkerRequest;
use App\Http\Requests\UpdateMarkerRequest;
use App\Models\Marker;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class MarkerController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Marker::class);

        $query = Marker::query()
            ->with('user');

        if ($type = request('type')) {
            $query->where('type', $type);
        }

        if ($status = request('status')) {
            $query->where('status', $status);
        }

        if (request('my_markers') && auth()->check()) {
            $query->where('user_id', auth()->id());
        }

        $sortBy = request('sort_by', 'latest');
        $sortDirection = request('sort_direction', 'desc');

        switch ($sortBy) {
            case 'latest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'owner_asc':
                $query->join('users', 'markers.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'asc')
                    ->select('markers.*');
                break;
            case 'owner_desc':
                $query->join('users', 'markers.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'desc')
                    ->select('markers.*');
                break;
            default:
                $query->latest();
                break;
        }

        $markers = $query->paginate(10)->withQueryString();

        return view('markers.index', [
            'markers' => $markers,
            'statuses' => $this->statuses(),
            'types' => $this->types(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Marker::class);

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
        $this->authorize('create', Marker::class);

        $marker = new Marker($request->validated());
        $marker->user()->associate($request->user());
        $marker->save();

        return redirect()
            ->route('markers.index')
            ->with('status', 'Marcador creado correctamente.');
    }

    public function edit(Marker $marker): View
    {
        $this->authorize('update', $marker);

        return view('markers.edit', [
            'marker' => $marker,
            'types' => $this->types(),
            'statuses' => $this->statuses(),
        ]);
    }

    public function update(UpdateMarkerRequest $request, Marker $marker): RedirectResponse
    {
        $this->authorize('update', $marker);

        $marker->update($request->validated());

        return redirect()
            ->route('markers.index')
            ->with('status', 'Marcador actualizado correctamente.');
    }

    public function destroy(Marker $marker): RedirectResponse
    {
        $this->authorize('delete', $marker);

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
}
