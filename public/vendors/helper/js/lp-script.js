var ip  = location.host;
const mysocket = io(ip+':3000');   

mysocket.on('connect', (sock) => {
    // mysocket.socket.sessionid;
    console.log('Connection established',mysocket.id);
    mysocket.emit('updateData', {
    // product: ["{{Auth::user()->id==1?'GOLD-I':'SILVER-I'}}"]
    product: ((typeof MyWatchScript !== 'undefined') ? MyWatchScript : [])
});
});

mysocket.on('updateData', (marketData) => {
    console.log('Received data:', marketData);
    if (marketData?.data?.Exchange !== undefined)
        setMarketValues(marketData);
});

mysocket.on('connect_error', (error) => {
    console.error('Socket.IO connection error:', error);
});

function setMarketValues(marketData) {
    // var Exchange = marketData.data.Exchange;
    var Exchange = '';
    var BuyPrice = marketData.data.BuyPrice;
    var InstrumentIdentifier = marketData.data.InstrumentIdentifier;
    var PriceChange = marketData.data.PriceChange;
    var PriceChangePercentage = marketData.data.PriceChangePercentage;

    var Options = ['BuyPrice', 'SellPrice', 'Open', 'LastTradePrice', 'High', 'Low', 'Close',
        'InstrumentIdentifier', 'PriceChange', 'PriceChangePercentage',];

    Options.forEach(opt_value => {

        if (marketData.data[opt_value] != undefined) {


            var curValue = $('.' + Exchange + InstrumentIdentifier + opt_value).html();

            $('.' + Exchange + InstrumentIdentifier + opt_value).html((marketData.data[opt_value])
                .toLocaleString('en-US', {
                    style: 'decimal',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));

            $("[pl-"+opt_value+"="+InstrumentIdentifier+"]").html((marketData.data[opt_value])
            .toLocaleString('en-US', {
                style: 'decimal',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));

            if (['BuyPrice', 'SellPrice', 'LastTradePrice'].indexOf(opt_value) !== -1) {

                var nowval = $('.' + Exchange + InstrumentIdentifier + opt_value).html();
                // console.log(curValue,nowval ,'<',(curValue<nowval),'==',(curValue==nowval),'>',(curValue>nowval));

                if (curValue < nowval)
                    $('td.' + Exchange + InstrumentIdentifier + opt_value).css('background-color',
                        '#28c76f');
                if (curValue > nowval)
                    $('td.' + Exchange + InstrumentIdentifier + opt_value).css('background-color',
                        '#ea5455');
                else
                    $('td.' + Exchange + InstrumentIdentifier + opt_value).css('background-color',
                        '#0C51C4');

            }
            // else if (['BuyPrice', 'SellPrice','LastTradePrice'].indexOf(opt_value) !== -1 && marketData.data['PriceChange'] < 0) {
            //     $('td.'+Exchange+InstrumentIdentifier+opt_value).css('background-color', '#BF2114');
            // }

            if (['PriceChange'].indexOf(opt_value) !== -1) {
                var iconlink = Exchange + InstrumentIdentifier + opt_value + 'icon';

                $('.' + iconlink).replaceWith(feather.icons[(marketData.data['PriceChange'] < 0 ?
                    'trending-down' : 'trending-up')].toSvg({
                    "class": (marketData.data['PriceChange'] < 0 ? 'text-danger' :
                        'text-success') + ' ' + iconlink
                }));

                $('.' + iconlink).closest('.avatar').removeClass((marketData.data['PriceChange'] < 0 ?
                    'bg-light-success' : 'bg-light-danger'));
                $('.' + iconlink).closest('.avatar').addClass((marketData.data['PriceChange'] > 0 ?
                    'bg-light-success' : 'bg-light-danger'));

                if (marketData.data['PriceChange'] < 0)
                    $('td.' + Exchange + InstrumentIdentifier + opt_value + 'icon').addClass('text-danger');
                else
                    $('td.' + Exchange + InstrumentIdentifier + opt_value + 'icon').removeClass('text-danger');
            }


        }
    });
}