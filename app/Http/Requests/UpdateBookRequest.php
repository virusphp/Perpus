<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class UpdateBookRequest extends StoreBookRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['title'] = 'required|unique:books,title,' .$this->route('book');
        return $rules; 
    }

    Public Function messages()
    {
        return [
            'amount.min' => 'Stok Buku minimal sesuai yang sedang dipinjam',
        ];
    }
}
