<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    // تستخدم في موضوع الصلاحيات يعني هل المستخدم مسموح انه يستدعي هاد الريكويست ولا لا authorize
    public function authorize(): bool
    {
        return true;
        // ازا رجعت خطا غير مسموح للمستخدم انه ينفذ
    }

    // return true; ازا رجعت ترو يسمح للمستخدم بتنفيذ هذه الصلاحية

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {

        $product = $this-> route('product', 0);
        $id = $product? $product->id : 0;

        return [
            'name'=>'required|max:255|min:3',
            'slug' => "required|unique:products,slug, $id",
            'category_id'=> 'nullable|int|exists:categories,id',
            'descripton'=> 'nullable|string',
            'short_description'=> 'nullable|string|max:500',
            'price'=>'required|numeric|min:0',
            'compare_price'=> 'nullable|numeric|min:0,gt:price',
            'status' => 'required| in:active,draft,archived',
            'image'=> 'required|image|max:2048|mimes:jpg,png',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image',
            // 'image'=> 'nullable|image|dimensions:min_width=400,min_height=300|max:400',//kilobayte
        ];
    }
    public function messages(): array

    {
         return
        //  [
        //     Response::HTTP_BAD_REQUEST
        //  ];
            [
                'required' => 'attribute field is required!!',
                'unique' => 'The value already exists!',
                'name.required' =>'The product name is mandatory!',
            ];
    }
}
