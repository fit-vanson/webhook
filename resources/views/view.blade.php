@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="tradingview-widget-container">
                <div id="tradingview_2c0d2"></div>
                <div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/symbols/NASDAQ-AAPL/" rel="noopener" target="_blank"><span class="blue-text">AAPL Chart</span></a> by TradingView</div>
                <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
                <script type="text/javascript">
                    new TradingView.widget(
                        {
                            "width": 980,
                            "height": 610,
                            "symbol": "NASDAQ:AAPL",
                            "interval": "D",
                            "timezone": "UTC+7",
                            "theme": "light",
                            "style": "1",
                            "locale": "vn",
                            "toolbar_bg": "#f1f3f6",
                            "enable_publishing": false,
                            "allow_symbol_change": true,
                            "container_id": "tradingview_2c0d2"
                        }
                    );
                </script>
            </div>

        </div>
    </div>
@endsection
