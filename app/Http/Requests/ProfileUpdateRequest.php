<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'profile_image' => ['nullable', 'image', 'max:1024'], // الحد الأقصى 1 ميجابايت
            'cover_image' => ['nullable', 'image', 'max:2048'], // الحد الأقصى 2 ميجابايت
            'bio' => ['nullable', 'string', 'max:500'],
            'birthdate' => ['nullable', 'date', 'before:today'],
        ];
    }
}
