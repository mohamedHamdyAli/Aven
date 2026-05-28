self.addEventListener('push', function (event) {
    let data = {};
    try { data = event.data.json(); } catch (e) { data = { title: 'New notification', body: event.data ? event.data.text() : '' }; }

    event.waitUntil(
        self.registration.showNotification(data.title || 'Notification', {
            body: data.body || '',
            icon: data.icon || '/themes/shop/default/images/logo.svg',
            data: { url: data.url || '/' }
        })
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    const url = event.notification.data.url || '/';
    event.waitUntil(clients.openWindow(url));
});
