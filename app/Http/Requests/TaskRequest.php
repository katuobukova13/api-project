<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string',
            'description' => 'string|max:255|nullable',
            'status' => Rule::in(TaskStatus::getValues()),
            'project_id' =>'required|exists:projects,id',
        ];
    }

    public function all($keys = null): array
    {
        $data = parent::all($keys);
        $data['project_id'] = $this->route('id');
        return $data;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['error' => 'Validation error', 'messages' => $validator->errors()], 500));
    }
}
