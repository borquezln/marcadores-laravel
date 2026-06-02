<?php

namespace App\Http\Requests;

use App\Models\Marker;

class UpdateMarkerRequest extends StoreMarkerRequest
{
    public function authorize(): bool
    {
        $marker = $this->route('marker');

        return $marker instanceof Marker
            && ($this->user()?->can('update', $marker) ?? false);
    }
}
