<?php

use Illuminate\Support\Facades\Broadcast;

// routes/channels.php

Broadcast::channel('App.Models.User.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

