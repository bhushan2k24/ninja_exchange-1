const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const axios = require('axios');

const app = express();
const server = http.createServer(app);
const io = socketIo(server);

const dotenv = require('dotenv').config();

const LaraServerUrl = process.env.APP_URL;


// External Socket For Market Data
const externalSocket = require('socket.io-client')('https://thedatamining.org:6003',{ rejectUnauthorized:   false,});
console.log(LaraServerUrl+'helper/get-trading-extensions');

var stock_array = ['NIFTY-I', 'BANKNIFTY-I'];

externalSocket.emit('addMarketWatch', {
    product: stock_array
});

// Optionally, you can also send the data to the Laravel application using HTTP request
axios.get(LaraServerUrl+'helper/get-trading-extensions')
.then(response => {
    stock_array = response.data;

    externalSocket.emit('addMarketWatch', {
        product: stock_array
    });
    // stock_array.forEach(function($val) {
    //     // Emit the 'addMarketWatch' event with the specified data
    //     externalSocket.emit('addMarketWatch', {
    //         product: $val
    //     });
    // });

    console.log('Data sent to Laravel:', response.data);
})
.catch(error => {
    console.error('Error sending data to Laravel:', error);
});

// Listen for data from the external socket.io server
externalSocket.on('marketWatch', (Marketdata) => {     
    // Broadcast the data to all connected clients
    io.emit('updateData', Marketdata);
    console.log('Received data from external server:', Marketdata);
});

// Socket.IO for laravel
io.on('connection', (socket) => {
    console.log('A user connected',socket.id);
        // Listen for data from the client
        // socket.on('updateData', (stock_array) => {
        //     console.log('Received data from client:', stock_array);
        //     stock_array?.product?.forEach(function($val) {
        //         // Emit the 'addMarketWatch' event with the specified data
        //         externalSocket.emit('addMarketWatch', {
        //             product: $val
        //         });
        //     });

        // });

    externalSocket.on('connect_error', (MarketError) => {
        console.error('Socket.IO connection error:', MarketError);
    });

    externalSocket.on('error', (MarketError) => {
        console.error('Socket.IO error:', MarketError);
    });


    // Handle disconnection
    socket.on('disconnect', () => {
        console.log('User disconnected');
    });
});

// Start the server
const port = 3000;
server.listen(port, () => {
    console.log(`Server is listening on port ${port}`);
});