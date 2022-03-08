<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('new-report', function () {
    // if you get here, you've been authenticated (within a custom middleware)
    return true;
});

Broadcast::channel('report-channel', function ($user) {
    // if you get here, you've been authenticated (within a custom middleware)
    return true;
});

Broadcast::channel('missingPerson-channel', function ($user) {
    // if you get here, you've been authenticated (within a custom middleware)
    return true;
});

Broadcast::channel('missingItem-channel', function ($user) {
    // if you get here, you've been authenticated (within a custom middleware)
    return true;
});

Broadcast::channel('complaint-channel', function ($user) {
    // if you get here, you've been authenticated (within a custom middleware)
    return true;
});

Broadcast::channel('feedback-channel', function ($user) {
    // if you get here, you've been authenticated (within a custom middleware)
    return true;
});

Broadcast::channel('inquiry-channel', function ($user) {
    // if you get here, you've been authenticated (within a custom middleware)
    return true;
});

Broadcast::channel('userVerification-channel', function ($user) {
    // if you get here, you've been authenticated (within a custom middleware)
    return true;
});

Broadcast::channel('order-channel', function ($user) {
    // if you get here, you've been authenticated (within a custom middleware)
    return true;
});
