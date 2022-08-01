var socket = null;
var socket_host = 'ws://127.0.0.1:6441';

initializeSocket = function() {
    try {
        if (socket == null) {
            socket = new WebSocket(socket_host);
            socket.onopen = function() {};
            socket.onmessage = function(msg) {};
            socket.onclose = function() {
                socket = null;
            };
        }
    } catch (e) {
        console.log(e);
    }
};
