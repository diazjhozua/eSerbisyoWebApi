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
        return [
            'success' => false,
            'message' => 'This request cannot be made because '.$modelName. ' is already '. $status,
        ];
    }

    public function statusMessage($oldStatus, $newStatus, $modelName) {
        return [
            'success' => true,
            'message' => $modelName.' successfully changed its status from '.$oldStatus.' to '.$newStatus,
        ];
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





}

