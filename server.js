const express = require('express');
const bodyParser = require("body-parser");
const app = express();
const http = require('http');
const server = http.createServer(app);
const io = require('socket.io').listen(server);
var clients = {};
const port = 8080;

server.listen(port);
app.use(bodyParser.urlencoded({extended: true}));
app.use(bodyParser.json());
app.post('/new-message', function (req, res) {
    console.log(req.body.notifiedUserIds);
    console.log(req.body.messageId);
    console.log(req.body.threadId);
    req.body.notifiedUserIds.map((id) => {
        const client = clients[id];
        if(client) {
            client.map((connection) => {
                connection.emit('messenger:newMessage', {
                    messageId: req.body.messageId,
                    threadId: req.body.threadId,
                });
            });
        }
    });
    res.send('');
});

io.sockets.on('connection', function (client) {
    const userId = client.handshake.query.userId;
    console.log('Client with userId ' + userId + ' has been connected');
    let connections = clients[userId] || [];
    connections.push(client);
    clients[userId] = connections;
});
