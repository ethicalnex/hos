<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicalRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role === 'doctor';
    }

    public function rules(): array
    {
        return [
            'appointment_id' => 'required|exists:appointments,id',
            'temperature' => 'nullable|string|max:10',
            'blood_pressure' => 'nullable|string|max:20',
            'pulse' => 'nullable|string|max:10',
            'respiratory_rate' => 'nullable|string|max:10',
            'weight' => 'nullable|string|max:10',
            'height' => 'nullable|string|max:10',
            'symptoms' => 'nullable|string',
            'diagnosis' => 'required|string',
            'treatment_plan' => 'required|string',
            'doctor_notes' => 'nullable|string',
        ];
    }
}