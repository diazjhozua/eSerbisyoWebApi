<?php
namespace App\Helper;

class Helper
{
    public static function instance()
    {
        return new Helper();
    }

    function storeSuccess($modelName, $model) {

    }

    function updateSuccess($modelName, $model) {

    }

    public function noItemFound($modelName) {
        return [
            'success' => false,
            'message' => 'No '.$modelName. ' id found',
        ];
    }
}

