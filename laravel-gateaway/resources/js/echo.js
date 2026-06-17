import Echo from "laravel-echo";

import Pusher from "pusher-js";
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: 'b04d953911ef4b1d2807', 
    cluster: 'eu',
    forceTLS: true,
});
