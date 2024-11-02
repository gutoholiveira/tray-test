<?php

namespace App\Http\Requests\Sale;

use App\Models\Sale;
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
            Sale::SELLER_ID => ['required', 'integer'],
            Sale::VALUE     => ['required', 'integer'],
            Sale::DATE      => ['required', 'date'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $seller_id = strip_tags(trim($this->seller_id));
        $value     = strip_tags(trim($this->value));
        $date      = explode('T', strip_tags(trim($this->date)));

        $this->merge([
            Sale::SELLER_ID => $seller_id,
            Sale::VALUE     => $value,
            Sale::DATE      => $date[0],
        ]);
    }
}
