<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Contracts\View\View;

class MapController extends Controller
{
    public function __invoke(): View
    {
        $typeLabels = $this->typeLabels();

        $markers = Marker::query()
            ->visibleOnMap()
            ->orderBy('title')
            ->get([
                'id',
                'title',
                'type',
                'latitude',
                'longitude',
                'address',
            ])
            ->map(fn (Marker $marker): array => [
                'id' => $marker->id,
                'title' => $marker->title,
                'type' => $marker->type,
                'type_label' => $typeLabels[$marker->type] ?? $marker->type,
                'latitude' => (float) $marker->latitude,
                'longitude' => (float) $marker->longitude,
                'address' => $marker->address,
            ])
            ->values()
            ->all();

        return view('map.index', [
            'markers' => $markers,
            'types' => $typeLabels,
            'defaultCenter' => config('map.default_center'),
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function typeLabels(): array
    {
        return [
            Marker::TYPE_PLACE => 'Lugar',
            Marker::TYPE_BILLBOARD => 'Cartel',
            Marker::TYPE_STAND => 'Puesto',
        ];
    }
}
