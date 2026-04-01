<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AbsenStoreRequest extends FormRequest
{
    // #region agent log
    private function debugLog(string $runId, string $hypothesisId, string $location, string $message, array $data = []): void
    {
        $payload = [
            'sessionId' => 'db331f',
            'runId' => $runId,
            'hypothesisId' => $hypothesisId,
            'location' => $location,
            'message' => $message,
            'data' => $data,
            'timestamp' => (int) round(microtime(true) * 1000),
        ];
        @file_put_contents(base_path('debug-db331f.log'), json_encode($payload) . PHP_EOL, FILE_APPEND);
    }
    // #endregion

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // #region agent log
        $this->debugLog(
            'pre-fix',
            'H2',
            'AbsenStoreRequest.php:prepareForValidation',
            'Incoming absen payload',
            [
                'client_id' => $this->input('client_id'),
                'group_id' => $this->input('group_id'),
                'has_data' => $this->filled('data'),
                'info' => $this->input('info'),
            ]
        );
        // #endregion
    }

    public function rules(): array
    {
        // #region agent log
        $this->debugLog(
            'pre-fix',
            'H1',
            'AbsenStoreRequest.php:rules',
            'Validation rules evaluated',
            [
                'client_rule' => 'exists:client,id_cli',
                'group_rule' => 'auto-resolved-by-backend',
            ]
        );
        // #endregion
        return [
            'client_id' => 'required|integer|exists:client,id_cli',
            'data' => 'required|string',
            'info' => 'required|string|in:IN,OUT',
            'lat' => 'nullable|string',
            'long' => 'nullable|string',
            'di' => 'nullable|integer|exists:absensi,id_absen'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // #region agent log
        $this->debugLog(
            'pre-fix',
            'H3',
            'AbsenStoreRequest.php:failedValidation',
            'Validation failed',
            [
                'errors' => $validator->errors()->toArray(),
            ]
        );
        // #endregion
        parent::failedValidation($validator);
    }
}
