<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class UpdateCartRequest extends FormRequest
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
            'product_id' => 'required|integer',
            'quantity' => 'required|integer'
        ];
    }
	
	public function after(): array
	{
		return [
			function (Validator $validator) {
				if (!Product::find($this->product_id)?->is_active) {
					$validator->errors()->add(
						'product_id',
						'Товар неактивен или удалён.'
					);
				}
			}
		];
	}
}
