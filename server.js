const redis = require('redis');
const express = require('express');
const socketServerConfig = require('./socket-server.config');
const app = express();
let server;
if (socketServerConfig.ssl.enable) {
    const fs = require('fs');
    const https = require('https');
    const clientCertificateAuth = require('client-certificate-auth');
    app.use(clientCertificateAuth(function () {
        return true;
    }));
    const sslOptions = {
        key: fs.readFileSync(socketServerConfig.ssl.pathToKey),
        cert: fs.readFileSync(socketServerConfig.ssl.pathToCert),
        requestCert: false
    };
    server = https.createServer(sslOptions, app);
} else {
    const http = require('http');
    server = http.createServer(app);
}
server.listen(socketServerConfig.port);
const io = require('socket.io').listen(server);

const clients = {};
const subscriber = redis.createClient();
if (socketServerConfig.redis.password) {
    subscriber.auth(socketServerConfig.redis.password);
}

subscriber.subscribe(socketServerConfig.redis.chanel);
subscriber.on('message', function (channel, message) {
    message = JSON.parse(message);
    if (message.hasOwnProperty('notifiedUserIds')) {
        message.notifiedUserIds.map((identityId) => {
            const connections = clients[identityId] || [];
            connections.map((client) => {
                client.emit('messenger:newMessage', {
                    messageId: message.messageId,
                    threadId: message.threadId,
                });
            });
        });
    }
});

io.sockets.on('connection', (client) => {
    const identityId = client.handshake.query.userId;
    let connections = clients[identityId] || [];
    connections.push(client);
    clients[identityId] = connections;
    client.on('disconnect', () => {
        let index = connections.indexOf(client);
        if (index >= 0) {
            connections.splice(index, 1);
        }
        clients[identityId] = connections;
        if (!clients[identityId].length) {
            delete clients[identityId];
        }
    });
});