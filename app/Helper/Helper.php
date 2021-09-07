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

    public function sameStatusMessage($status, $modelName,) {
        $initialStatus = '';
        switch($status) {
            case 1:
                $initialStatus = '"For Approval"';
                break;
            case 2:
                $initialStatus = '"Approved"';
                break;
            case 3:
                $initialStatus = '"Denied"';
                break;
            case 4:
                $initialStatus = '"Resolved"';
                break;
        }

        return [
            'success' => false,
            'message' => 'This request cannot be made because the '.$modelName. ' is already "'.$initialStatus.'"',
        ];
    }

    public function statusMessage($oldStatus, $newStatus, $modelName) {

        $initialStatus = '';
        switch($oldStatus) {
            case 1:
                $initialStatus = '"For Approval"';
                break;
            case 2:
                $initialStatus = '"Approved"';
                break;
            case 3:
                $initialStatus = '"Denied"';
                break;
            case 4:
                $initialStatus = '"Resolved"';
                break;
        }

        $message = $modelName.' is successfully changed its status from '.$initialStatus;
        switch($newStatus) {
            case 1:
                $message =   $message.' to "For Approval"';
                break;
            case 2:
                $message = $message.' to "Approved"';
                break;
            case 3:
                $message = $message.' to "Denied"';
                break;
            case 4:
                $message = $message.' to "Resolved"';
                break;
        }

        return $message;
    }

    public function noItemFound($modelName) {
        return [
            'success' => false,
            'message' => 'No '.$modelName. ' id found',
        ];
    }
}

