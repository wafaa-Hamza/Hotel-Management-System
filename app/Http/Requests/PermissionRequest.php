<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $updateUserPermissionsURL = request()->segment(count(Request()->segments())-1);
        if($updateUserPermissionsURL == "updateUserPermissions")
        {
            return [
                "permissionName.*" => 'required|exists:permissions,name'
            ];
        }

    }
}