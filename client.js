const io = require('socket.io-client');

const socket = io.connect('https://thedatamining.org:6003', {
    rejectUnauthorized:   false,
  }); // Replace with your Socket.IO server URL

var stock_array = ['GOLD-I', 'SILVER-I', 'SILVERM-I', 'SILVERMIC-I', 'AUDJPY', 'AUDUSD', 'CADJPY', 'EURUSD',
                   'GBPUSD', 'NIFTY-I', 'BANKNIFTY-I', 'NIFTY23122821000CE', 'EURINR24NOVFUT'];

stock_array.forEach((val) => {
  // Emit the 'addMarketWatch' event with the specified data
  socket.emit('addMarketWatch', { product: val });
});

socket.on('marketWatch', (marketData) => {
  console.log('Received marketWatch event with data:', marketData);
  // Handle the received market data as needed
});

socket.on('connect_error', (error) => {
    console.error('Socket.IO connection error:', error);
});

socket.on('error', (error) => {
    console.error('Socket.IO error:', error);
});

// Handle disconnection
socket.on('disconnect', () => {
  console.log('Disconnected from the server');
});