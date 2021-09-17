<?php
namespace App\Helper;

class Helper
{
    public static function instance()
    {
        return new Helper();
    }

    function storeSuccess($model) {
        return [
            'success' => true,
            'message' => $model. ' is successfully created',
        ];
    }

    public function noItemFound($modelName) {
        return [
            'success' => false,
            'message' => 'No '.$modelName. ' id found',
        ];
    }


    function itemFound($model) {
        return [
            'success' => true,
            'message' => 'Found '.$model.' data',
        ];
    }

    function updateSuccess($model) {
        return [
            'success' => true,
            'message' => $model. ' is successfully updated',
        ];
    }

    function destroySuccess($model) {
        return [
            'success' => true,
            'message' => $model. ' is successfully deleted',
        ];
    }

    function noted($model) {
        return [
            'success' => true,
            'message' => $model. ' is successfully noted',
        ];
    }

    function alreadyNoted($model) {
        return [
            'success' => false,
            'message' => $model. ' is already noted or you cannot update the status when the '.$model. ' is ignored',
        ];
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

    public function respondMessage($oldStatus, $newStatus, $modelName) {

        $initialStatus = '';
        switch($oldStatus) {
            case 1:
                $initialStatus = '"Pending"';
                break;
            case 2:
                $initialStatus = '"Ignored"';
                break;
            case 3:
                $initialStatus = '"Invalid"';
                break;
            case 4:
                $initialStatus = '"Noted"';
                break;
        }

        $message = $modelName.' is successfully changed its status from '.$initialStatus;
        switch($newStatus) {
            case 1:
                $message =   $message.' to "Pending"';
                break;
            case 2:
                $message = $message.' to "Ignored"';
                break;
            case 3:
                $message = $message.' to "Invalid"';
                break;
            case 4:
                $message = $message.' to "Noted"';
                break;
        }

        return $message;
    }



    public function noEditAccess() {
        return [
            'success' => false,
            'message' => 'You  cannot edit this field',
        ];
    }

    public function noUpdateAccess() {
        return [
            'success' => false,
            'message' => 'You  cannot update this field',
        ];
    }

    public function noDeleteAccess() {
        return [
            'success' => false,
            'message' => 'You cannot delete this field',
        ];
    }


}

