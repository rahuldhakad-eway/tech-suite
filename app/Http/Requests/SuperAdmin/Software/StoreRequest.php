<?php

namespace App\Http\Requests\SuperAdmin\Software;

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
        $data = [
            'category_id' => 'required',
            'description' => 'required',
            'logo' => 'required'
        ];

        return $data;
    }

}
