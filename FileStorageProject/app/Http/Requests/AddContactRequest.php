<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $email = auth()->user()->email;
        $emailContact = auth()->user()->contacts->pluck('email');
        $emailContact->add($email);

        return [
            "email" => "required|not_in:" . $emailContact->implode(',') . "|email:rfc|exists:users,email"
        ];
    }
}
