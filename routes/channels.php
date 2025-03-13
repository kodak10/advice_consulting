<?php

use Illuminate\Support\Facades\Broadcast;

// routes/channels.php

Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});


