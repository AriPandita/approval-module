<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ResponseHelper;

class RequesterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id_start' => 'required|integer', 
            'status' => 'nullable|integer', 
            'keyword' => 'nullable|string', 
            'limit' => 'nullable|integer',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
       $response = ResponseHelper::errorResponse('Failed get data', $validator->errors(), 400);
       throw new HttpResponseException($response);
    }
}
