const express = require('express');
const app = express();
const http = require('http');
const server = http.createServer(app);
const io = require('socket.io').listen(server);
var clients = {};
const port = 8080;

server.listen(port);
app.get('/new-message', function (req, res) {
    console.log('Request for user with HASH ' + req.query.hash + ' has been geted');
    sendNotification(req.query.hash, req.query.sender);
    res.send('')

});

function sendNotification(hash, notification) {
    for (let i in clients[hash]) {
        console.log('Send emit to user with HASH: ' + hash);
        clients[hash][i].emit('notification', notification)
    }
}

io.sockets.on('connection', function (client) {
    const userId = client.handshake.query.userId;
    console.log('Client with userId ' + userId + ' has been connected');
    let connections = clients[userId] || [];
    connections.push(client);
    clients[userId] = connections;
});
