<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ScanCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rfid_uid' => ['required', 'string', 'max:64'],
            'amount' => ['required', 'numeric', 'min:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'rfid_uid.required' => 'RFID UID wajib diisi.',
            'rfid_uid.string' => 'RFID UID harus berupa string.',
            'rfid_uid.max' => 'RFID UID terlalu panjang.',
            'amount.required' => 'Jumlah pembayaran wajib diisi.',
            'amount.numeric' => 'Jumlah pembayaran harus berupa angka.',
            'amount.min' => 'Minimum pembayaran adalah Rp 500.',
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
