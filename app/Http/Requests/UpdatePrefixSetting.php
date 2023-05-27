<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePrefixSetting extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'invoice_prefix' => 'required',
            'estimate_prefix' => 'required',
            'credit_note_prefix' => 'required',
            'contract_prefix' => 'required',
            'order_prefix' => 'required',
        ];
    }

}
