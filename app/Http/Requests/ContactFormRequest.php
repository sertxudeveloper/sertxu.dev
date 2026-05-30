<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\TurnstileRule;
use Illuminate\Foundation\Http\FormRequest;

final class ContactFormRequest extends FormRequest
{
    /**
     * The URI to redirect to if validation fails.
     *
     * @var string
     */
    protected $redirect = '/#contact';

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:1000'],
            'cf-turnstile-response' => ['exclude', 'required', 'string', new TurnstileRule],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return [
            'cf-turnstile-response' => 'Verify you are human',
        ];
    }
}
