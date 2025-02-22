<?php

use Illuminate\Support\Facades\Broadcast;

// routes/channels.php

Broadcast::channel('devis-create', function ($user) {
    return (bool) $user; // Renvoie `true` si l'utilisateur est connecté
});



