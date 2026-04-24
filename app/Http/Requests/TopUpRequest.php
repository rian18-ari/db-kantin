<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TopUpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:10000', 'max:10000000'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User ID wajib diisi.',
            'user_id.exists' => 'User tidak ditemukan.',
            'amount.required' => 'Jumlah top-up wajib diisi.',
            'amount.numeric' => 'Jumlah top-up harus berupa angka.',
            'amount.min' => 'Minimum top-up adalah Rp 10.000.',
            'amount.max' => 'Maksimum top-up adalah Rp 10.000.000.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
