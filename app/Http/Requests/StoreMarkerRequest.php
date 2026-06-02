<?php

namespace App\Http\Requests;

use App\Models\Marker;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMarkerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Marker::class) ?? false;
    }

    /**
     * @return array<string, array<int, string|ValidationRule>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in($this->types())],
            'status' => ['required', 'string', Rule::in($this->statuses())],
            'title' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    /**
     * @return array<int, string>
     */
    private function types(): array
    {
        return [
            Marker::TYPE_PLACE,
            Marker::TYPE_BILLBOARD,
            Marker::TYPE_STAND,
        ];
    }

    /**
     * @return array<int, string>
     */
    private function statuses(): array
    {
        return [
            Marker::STATUS_ACTIVE,
            Marker::STATUS_INACTIVE,
            Marker::STATUS_REMOVED,
        ];
    }
}
