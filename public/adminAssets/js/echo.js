import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'reverb',
//     key: import.meta.env.VITE_REVERB_APP_KEY,
//     wsHost: import.meta.env.VITE_REVERB_HOST,
//     wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
//     wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });

window.Echo.channel('devis.notifications')
    .listen('.devis.created', (event) => {
        // Exemple : Ajouter une notification à la liste de notifications
        let notificationList = document.getElementById('notification-list');
        let notificationItem = document.createElement('li');
        notificationItem.classList.add('dropdown-item');
        notificationItem.innerText = `Nouveau devis créé : ${event.devis_num}`;
        notificationList.appendChild(notificationItem);

        // Mettez à jour l'icône de notification
        let notificationIcon = document.querySelector('.nav-icon .notification');
        notificationIcon.classList.add('bg-primary');
    });
