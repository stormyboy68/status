<?php
namespace ASB\Status\App\Requests;

use Illuminate\Support\Facades\Validator;

class StatusRequest
{

    public static function rules($value)
    {
        $validator = Validator::make(['title' => $value], [
            'title' => 'required|unique:statuses|max:255',
        ]);
        if ($validator->fails()) {
            return true;
        }
    }
}
