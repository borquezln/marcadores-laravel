<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->route('user');

        return $user instanceof User && $this->user()?->can('update', $user);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'role' => [
                'required',
                Rule::in([
                    User::ROLE_ADMIN,
                    User::ROLE_EDITOR,
                    User::ROLE_VIEWER,
                ]),
            ],
            'status' => [
                'required',
                Rule::in([
                    User::STATUS_PENDING,
                    User::STATUS_ACTIVE,
                    User::STATUS_DISABLED,
                ]),
            ],
        ];
    }
}
