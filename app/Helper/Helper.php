<?php
namespace App\Helper;

class Helper
{
    public static function instance()
    {
        return new Helper();
    }

    function likeStatus($model, $isLike) {
        $message = $isLike ? 'liked': 'unliked';
        return [
            'success' => true,
            'message' => $model. ' is '. $message .' successfully',
            'state' => $isLike ? 'liked': 'unliked',
        ];
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


}

