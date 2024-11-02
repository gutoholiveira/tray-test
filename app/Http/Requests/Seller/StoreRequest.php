<?php

namespace App\Http\Requests\Seller;

use App\Models\Seller;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            Seller::NAME  => ['required', 'string'],
            Seller::EMAIL => ['required', 'email'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $name  = strip_tags(trim($this->name));
        $email = strip_tags(trim($this->email));

        $this->merge([
            Seller::NAME  => $name,
            Seller::EMAIL => $email,
        ]);
    }
}
