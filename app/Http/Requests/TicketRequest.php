<?php

namespace App\Http\Requests;

use Coderflex\LaravelTicket\Enums\Priority;
use Coderflex\LaravelTicket\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TicketRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'message' => ['required', 'string'],
            //'categories'  => ['exists:categories,id'],
            //'labels'      => ['exists:labels,id'],
            'priority' => ['required', new Enum(Priority::class)],
            'status' => ['nullable', new Enum(Status::class)],
            //'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
            'files' => ['nullable', 'array', 'max:5'],
            'files.*' => ['max:5120'],
            'comment' => ['string', 'nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
    public function messages()
    {
        return [
            'files.*.max' => 'There is a file more than 5 MB.',
        ];
    }
}
