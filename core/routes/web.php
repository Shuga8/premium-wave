<?php

use App\Http\Controllers\DepositController;
use Illuminate\Support\Facades\Route;

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::get('cron', 'CronController@cron')->name('cron');

// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{ticket}', 'replyTicket')->name('reply');
    Route::post('close/{ticket}', 'closeTicket')->name('close');
    Route::get('download/{ticket}', 'ticketDownload')->name('download');
});
Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');
Route::get('ws', 'WsContoller@ws');

Route::get('deposit', [DepositController::class, 'index'])->name('user.deposit.index');
Route::get('deposit/new', [DepositController::class, 'new'])->name('user.deposit.new');
Route::post('deposit', [DepositController::class, 'store'])->name('user.deposit.store');

Route::controller("TradeController")->prefix('trade')->group(function () {
    Route::get('/order/book/{symbol}', 'orderBook')->name('trade.order.book');
    Route::get('pairs', 'pairs')->name('trade.pairs');
    Route::get('history/{symbol}', 'history')->name('trade.history');
    Route::get('order/list/{pairSym}', 'orderList')->name('trade.order.list');
    Route::get('/{symbol?}', 'trade')->name('trade');
});

Route::controller("CommoditiesController")->prefix('commodity')->group(function () {
    Route::get('history/{symbol}', 'history')->name('commodity.history');
    Route::get("all", 'all')->name('commodity.all');
    Route::get('/{symbol?}', 'index')->name('commodity');
    Route::post('store', 'store')->name('commodity.store');
    Route::get('cashout/{id}', 'cashout')->name('commodity.cashout');
});

Route::controller("StocksController")->prefix('stock')->group(function () {
    Route::get('history/{symbol}', 'history')->name('stock.history');
    Route::get("all", 'all')->name('stock.all');
    Route::get('/{symbol?}', 'index')->name('stock');
    Route::post('store', 'store')->name('stock.store');
    Route::get('cashout/{id}', 'cashout')->name('stock.cashout');
});

Route::controller('WaveController')->prefix('wave')->group(function () {
    Route::get('', 'index')->name('wave');
    Route::post('store', 'store')->name('wave.store');
    Route::get('open-trades', 'getOpenTrades')->name('wave.open');
    Route::get('pending-trades', 'getPendingTrades')->name('wave.pending');
    Route::get('trades-history', 'getTradesHistory')->name('wave.history');
    Route::get('end-running-trade/{id}', 'endTrade')->name('name.end-trade');
    Route::get('delete-pending-trade/{id}', 'deletePendingTrade')->name('wave.delete-pending');
    Route::get('coin-convert/{symbol}/{apikey}', 'coinConvert')->name('coinConvert');
});

Route::namespace('P2P')->group(function () {
    Route::controller("HomeController")->prefix('p2p')->group(function () {
        Route::get("/advertiser/{username}", 'advertiser')->name('p2p.advertiser');
        Route::get("/{type?}/{coin?}/{currency?}/{paymentMethod?}/{region?}/{amount?}", 'p2p')->name('p2p');
    });
});

Route::controller('SiteController')->group(function () {
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/pwa/configuration', 'pwaConfiguration')->name('pwa.configuration');
    Route::get('/market/list', 'marketList')->name('market.list');
    Route::get('/crypto/list', 'cryptoCurrencyList')->name('crypto_currency.list');
    Route::get('/market', 'market')->name('market');
    Route::get('/about-us', 'about')->name('about');
    Route::get('/blogs', 'blogs')->name('blogs');
    Route::get('/crypto-currency', 'crypto')->name('crypto_currencies');
    Route::get('/crypto/currency/{symbol}', 'cryptoCurrencyDetails')->name('crypto.details');
    Route::post('/contact', 'contactSubmit');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');
    Route::post('/subscribe', 'subscribe')->name('subscribe');
    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');
    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');
    Route::get('blog/{slug}/{id}', 'blogDetails')->name('blog.details');
    Route::get('policy/{slug}/{id}', 'policyPages')->name('policy.pages');
    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');
    Route::post('pusher/auth/{socketId}/{channelName}', "pusherAuthentication");
    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
});
