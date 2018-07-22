var express = require('express');
console.log('Express load succesfully');

//Для шифрования данных
// var crypto = require('crypto')
// console.log('Crypto load succesfully')
// var iv = '1234567890123456'
// var key = 'MySecretKey12345'
// var cipher = crypto.createCipheriv('aes-128-cbc', key, iv)
// var decipher = crypto.createDecipheriv('aes-128-cbc', key, iv)

//Конец шифрования данных
/*Опции SSL сертификатов */
// var SSLoptions = {
//     // key: fs.readFileSync('/etc/letsencrypt/live/avto.guru/privkey.pem'),
//     // cert: fs.readFileSync('/etc/letsencrypt/live/avto.guru/cert.pem'),
// //  requestCert:true
// }
/*Конец опциий*/
var app = express();
var http = require('http');
console.log('Http load succesfully');
var server = http.createServer(app);
var io = require('socket.io').listen(server);
console.log('Socket.io load succesfully');
var clients = {};
var connection = null;
var port = 8080;


server.listen(port)
console.log('Server has beend started on port ' + port)
app.get('/new-message', function (req, res) {
    // console.log(req.query.hash, clients)
    //var hash = decipher.update(req.params.hash, 'hex', 'binary');
    console.log('Request for user with HASH ' + req.query.hash + ' has been geted')
    // var decrypted = decipher.update(req.params.sender, 'hex', 'binary');
    sendNotification(req.query.hash, req.query.sender)
    res.send('')

})

function sendNotification(hash, notification) {
    for (var i in clients[hash]) {
        console.log('Send emit to user with HASH: ' + hash)
        clients[hash][i].emit('notification', notification)
    }
}

io.sockets.on('connection', function (client) {
    var hash = client.handshake.query.hash
    console.log('Client with HASH ' + hash + ' has been connected')

    var connections = clients[hash] || []
    connections.push(client)
    clients[hash] = connections

    console.log('New connection from ' + client.handshake.address + ':' + client.port)
})
