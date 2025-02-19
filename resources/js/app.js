import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '5a299c7322c90ce58687',  // Utilisez votre clé Pusher ici
    cluster: 'eu',  // Utilisez votre cluster Pusher ici
    forceTLS: true
});
