# HAL 10K - PHP Trading bot

After losing some money on Bitcoin exchanges, I decided to develop my own Trading bot/helper. This bot acts with pre-defined parameters based on statistics and not with the emotion of the moment, so it's much easier to perform trading operations.

**HAL10K** is a Bitcoin trading/helper bot written in PHP (open source). It was developed to be used on the API MtGox, but can be easily adapted to other exchanges. Their decisions are based on customizable parameters and basic techniques for market analysis. It also works in semi-automatic mode, in which each loss(stop-loss) asks via Twitter for remote confirmation of a bot operator. Besides the "Live Trading" so it also runs simulations "Backtesting" using "Fake Balance" and historical raw data from **BitcoinCharts.com**. All bot actions(buying/selling) and alerts(high volume detected) are notified via Twitter. Log results is accompanied by HTTP control where the bot displays its own performance chart panel.

**HAL10K** was developed "from scratch", without taking as none basis of existing algorithms/trading techniques. This project is a learning exercise for Trading/Economics for me, my experience is limited in programming and Bitcoin. Yes, I'm already getting real profits with this trading bot, and the idea of ​​open source code assumed that sharing experiences, together we can greatly improve the algorithm and achieve even greater profits.

**Contact**: @[intrd](http://twitter.com/intrd) at Twitter or [http://dann.com.br/2013/bitcoin-php-open-source-hal10k-trading-bot/](http://dann.com.br/2013/bitcoin-php-open-source-hal10k-trading-bot/)   
**Donations**: BTC Wallet: [19kAWVN553KyoU7vx9pYXu8ShVUsPVXzig](https://blockchain.info/address/19kAWVN553KyoU7vx9pYXu8ShVUsPVXzig)   
I'm open to questions/suggestions, any collaboration in the code is welcome.
The author of this project and its contributors are not responsible for any losses.

* Tks to
   - @thaleslaray - Starter investor
   - @rafaelchaguri - Beta tester 
   - Daniel Fraga: [http://www.youtube.com/user/DanielFragaBR](http://www.youtube.com/user/DanielFragaBR) 
   - Bitcoin Developers FB community: https://www.facebook.com/groups/bitcoindevelopersbr/204238816428548/
   - Wladimir Crippa & FB Bitcoin Brasil community: [http://www.facebook.com/groups/480508125292694](http://www.facebook.com/groups/480508125292694) 
   - Reddit: [/r/bitcoin/hal10k thread](http://www.reddit.com/r/Bitcoin/comments/1u0bd9/hal10k_php_open_source_trading_helper_bot/) 
   - Bitcoin talk: [hal10k thread](https://bitcointalk.org/index.php?topic=391630)

## WEB Controle panel & console

![](http://dann.com.br/web.png)
Live statistics, sudden and death mode remote control

## Backtesting sample

![](http://dann.com.br/chart_sample.png)

>Período de simulação: 21/12/2013 até 27/12/2013      
>Iniciando com 1.03161308 BTC @ 700    
>$ask [btc0/usd:699.43] @ $678 (lucro) #transaction  
>$bid [btc1.07/usd:0] @ $649.43505 (lucro) #transaction  
>$ask [btc0/usd:721.03] @ $672.513 (lucro) #transaction  
>$bid [btc1.08/usd:0] @ $665.41 (lucro) #transaction     
>$ask [btc0/usd:775.37] @ $718.795 (lucro) #transaction  
>$bid [btc1.14/usd:0] @ $679.995 (lucro) #transaction    
>$ask [btc0/usd:795.73] @ $701 (lucro) #transaction  
>$bid [btc1.14/usd:0] @ $701 (nulo) #transaction     
>Finalizando com 1.14 BTC @ 701 

![](http://dann.com.br/console.png)
>Console sample

## Twitter notifications sample

![](http://dann.com.br/hal_twitter.png)

Bot notifying your operator a large volume that just happened;
Bot requesting help on Stop-Loss decision;
Bot informing a purchase(loss) made ​​starting from a Sudden Mode remote command, Buying 1.03BTC when the value of btc/usd was at 830USD.

## Main features & configs

* Exchanges
   - MTGox API (MTGox app data. If you do not have access to the API gox, visit: https://www.mtgox.com/security and create your key (with read/write)); 
* Text result logs
* Audible beeps when running transactions (differentiated for profits/losses)
* Trading parameters
   - up_diff - (sell) points profit (in USD) above the purchase price;   
   - up_diff_inv - (sell) stop loss (in USD) below the purchase price; 
   - down_diff - (buy) points profit (in USD) below the selling price;    
   - down_diff_inv - (buy) stop loss (in USD) above the selling price;     
   - percentual - Minimum percentage of profit on the purchase (Use the current fee applied by MtGox);    
   - secure_ticker - Security value that prevents the bot to make sales below a certain value;   
   - interval - Bot loop interval (in seconds);   
   - timeout - Timeout in seconds for completion of the bid/ask;  
   - sudden_mode - When active, the bot makes a purchase at the sale price, or a sale at the purchase price. Used only when there is a need for immediate order processing. It will be disabled after the order is processed; (ATTENTION)      
   - reverse_prices - Same as Sudden, but definite. It will never turned off; (ATTENTION)    
   - manualstoploss - When active, in every action stop-loss requests remote confirmation of the bot operator; (ATTENTION)    
   - dire - Amount of past intervals used in the identification of market direction;   
   - dire_limbo - Minimum variation (in USD) to define whether the direction is out of limbo or not (limbo: when the bot still trying to set the direction of the market);     
   - vol_limbo - Minimum volume (in USD) to consider an abnormal change at the volume of an interval to another;     
* Backtesting
   - fake - Turns on/off simulation (backtesting); (ATTENTION)    
   - fake_btc_balance - Initial amount of BTC for the simulation;    
   - fake_btc_usd_buyedprice - Initial purchase price of BTC fake balance;    
   - fake_datetime_of_firstbid - //Initial datetime (same as first line of fakegox_tickers file below);
   - fakegox_tickers - Access http://bitcoincharts.com/charts/mtgoxUSD, set the period (must be a period that supports interval 1min), click Raw Data, copy/paste the contents of the table in a TXT file and replaces tabulations by "," comma);
* Notificações via Twitter
   - enable_tweet - Enable/disable Twitter notifications;   
   - twitter_oauth - Data to access the Twitter API, if you do not have, go to: https://dev.twitter.com/apps and create your key w/ read/write permission access;
   - twitter_users - Twitter @users to be notified;     
   - Alert bot operator on high volumes;  
* Graphic WEB interface
   - Graphic results log (period, last action, asks/bids);  
  * HTTP control panel
         - Sudden mode (Activates sudden mode immediately, password is the two digit minute of the datetime displayed in the header above the chart, eg: "01" for datetime: 2013-12-30 04:01:18. It was done this way to prevent you run sudden-mode twice accidentally on a refresh page);  
         - Death mode (Freeze the bot temporarily. The Password is "meuovo123654", can be changed in the file: makesudden.php)     

**Notes**
- The bot needs at least one bid and ask to generate the graphic chart correctly.
- MtGox need at least 0.02BTC to process a transaction.

**Todo**
- Implement EMA short/long crossover trading technique
- Paper trading (fake trading with live data)
- Implement Vircurex and BTC-e API
- Auto fetch BitcoinCharts data via cUrl
- Implement .bat Windows looping file to a version for Linux Shell script+crontab 

**Installing and running**   

Must be configured in *configs.php* and started on *hal10k.bat* (remember to edit *hal10k.bat* to correct default path: *C:\xampp\htdocs\hal10k*)   

This project was developed in the environment described below, so please try to use a similar configuration
* XAMPP version 1.7.7 (not included in the project)
   * Apache 2.2.21 (running as service to avoid strange problems in pChart lib)   
   * PHP 5.3.8 (VC9 X86 32bit thread safe) + PEAR   

* Libs utilizadas (included in the project)
   * pChart 2.1.3 for graphics generation;  
   * tmhOAuth for Twitter notifications;  

**Changelog**
* v1.0 beta 2
   * - First public version

>HAL10K Bitcoin trading bot
>@copyright (C) http://dann.com.br - @intrd (Danilo Salles) <contact@dann.com.br>
>HAL10K is free software; you can redistribute it and/or
>modify it under the terms of the GNU General Public License 2
>as published by the Free Software Foundation.
>HAL10K is distributed in the hope that it will be useful,
>but WITHOUT ANY WARRANTY; without even the implied warranty of
>MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
>GNU General Public License for more details.
`
