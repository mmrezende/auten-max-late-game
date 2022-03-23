<?php

namespace App\Http\Requests;

use App\Helpers\DBSizes;
use App\Helpers\DBTypes;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Http\Requests\DefaultRequest;

class UpdateUserRequest extends DefaultRequest
{
    // /**
    //  * Prepare the data for validation.
    //  *
    //  * @return void
    //  */
    // protected function prepareForValidation()
    // {
    //     $this->merge([
    //         'email' => Str::lower($this->email),
    //         'identification_type'=> Str::upper($this->identification_type),
    //         'identification_value'=> Str::replace('/[^0-9]*/g','',$this->identification_value),
    //         'phone'=> Str::replace('/[^0-9]*/g','',$this->phone)
    //     ]);
    // }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('user')->id;
        return [
            'email' => 'email|unique:users,email,' . $id . '|max:' . DBSizes::STRING,
            'name'=> 'string|min:2|max:' . DBSizes::STRING,
            'identification_type'=> Rule::in(DBTypes::IDENTIFICATION_TYPE),
            'identification_value'=> 'cpf|required_with:identification_type|unique:users,identification_value,' . $id,
            'phone'=> 'integer|digits_between:10,14',
        ];
    }
}
