# HAL 10K - Bitcoin PHP Trading bot

* [Official post -> HAL 10K @ PHP Bitcoin trading & helper bot](http://dann.com.br/hal-10k-php-trading-helper-bot/)
* [GitHUB stable Source code](https://github.com/intrd/bitcoin/tree/master/hal10k)
* [Bitcointalk announcemnt thread](https://bitcointalk.org/index.php?topic=391630)

After losing some money on Bitcoin exchanges, I decided to develop my own Trading bot/helper. This bot acts with pre-defined parameters based on statistics/strategies and not with the emotion of the moment, so it's much easier to perform trading operations.

**HAL10K** is a Bitcoin trading/helper bot written in PHP (open source). It was developed to be used on the API MtGox, but can be easily adapted to other exchanges. Their decisions are based on customizable parameters and trading techniques for market analysis. It also works in semi-automatic mode, in which each loss(stop-loss) asks via Twitter for remote confirmation of a bot operator. Besides the "Live Trading" so it also runs simulations "Backtesting/Paper trading" using "Fake Balance" and historical raw data from **Bitcoincharts.com**. All bot actions(buying/selling) and alerts(high volume detected) are notified via Twitter. Log results is accompanied by HTTP Control panel where the bot displays its own performance chart panel.

**HAL10K** was developed "from scratch", without taking basis of existing algorithms/trading techniques. This project is a learning exercise on Trading/Economics for me, when started this project my experience was limited in programming and Bitcoin. Yes, I'm already getting real profits with this trading bot, and the idea of ​​Open Source assumed that sharing experiences, together we can greatly improve the algorithm and achieve even greater profits.

>Please, do not go to the Live trading before mastering the paper trading.
>The author of this project and its contributors are not responsible for any losses.

####WEB Control panel & console
Daemon

![](http://dann.com.br/content/images/2015/08/687474703a2f2f64616e6e2e636f6d2e62722f636f6e736f6c652e706e67-1.png)

Live statistics, "Sudden" and "Death mode" remote control.

![](http://dann.com.br/content/images/2015/08/687474703a2f2f692e696d6775722e636f6d2f4c53736d69594b2e706e67.png)


####Backtesting sample
```language-shell
Period: 21/12/2013 até 27/12/2013      
Starting w/ 1.03161308 BTC @ 700    
$ask [btc0/usd:699.43] @ $678 (lucro) #transaction  
$bid [btc1.07/usd:0] @ $649.43505 (lucro) #transaction  
$ask [btc0/usd:721.03] @ $672.513 (lucro) #transaction  
$bid [btc1.08/usd:0] @ $665.41 (lucro) #transaction     
$ask [btc0/usd:775.37] @ $718.795 (lucro) #transaction  
$bid [btc1.14/usd:0] @ $679.995 (lucro) #transaction    
$ask [btc0/usd:795.73] @ $701 (lucro) #transaction  
$bid [btc1.14/usd:0] @ $701 (nulo) #transaction     
Ending w/ 1.14 BTC @ 701
```
####Trading bot EMA Short/Long Crossover based
![](http://dann.com.br/content/images/2015/08/web.png)
Perfect for high frequency trading, better than simple trading method

####Twitter notifications sample
![](http://dann.com.br/content/images/2015/08/hal_twitter.png)

Bot notifying your operator a large volume that just happened;
Bot requesting help on Stop-Loss decision;
Bot informing a purchase(loss) made ​​starting from a Sudden Mode remote command, Buying 1.03BTC when the value of btc/usd was at 830USD.

####Main features & configs
* Exchanges
  * MTGox API (MTGox app data. If you do not have access to the API gox, visit: https://www.mtgox.com/security and create your key (with read/write)); 
* Text result logs
* Audible beeps when running transactions (differentiated for profits/losses)
* Trading parameters
   - up_diff - (sell) points profit (in USD) above the purchase price;   
   - up_diff_inv - (sell) stop loss (in USD) below the purchase price; 
   - down_diff - (buy) points profit (in USD) below the selling price;    
   - down_diff_inv - (buy) stop loss (in USD) above the selling price;     
   - percentual - Minimum percentage of profit on the purchase (Use the current fee applied by MtGox);    
   - secure_ticker - Security value that prevents the bot to make sales below a certain value;  
   - emacross - Turn on EMA crossover method (if emacross=true, Simple Market Direction Method automatically is turned off);
   - emaShort - EMA short period(in seconds) for EMA crossover method;
   - emaLong - EMA long period(in seconds) for EMA crossover method;
   - emaDiff - EMA difference between short and long crossover;
   - last_two_orders - Base next move on the last two transactions value; 
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
   - paper - Paper trading is simulation with Real Live Tickers but w/ fake money balance. (If Paper trading is true, $fake needs to be defined to "true")
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

> The bot needs at least one bid and ask to generate the graphic chart correctly.
> MtGox need at least 0.02BTC to process a transaction.

####Installing: 1click-to-run (easy)
* Download here: [hal10k_1click.zip](https://mega.nz/#!3YgVFC7S!KJ2S-T2Z9oXtpdL-gOkiFXBNXpbiolqQDZ1hjo58X_M) w/ apache+php pre-configured, just run the: start_hal10k.bat

####Installing: manual (advanced)
**GitHUB stable Source code**: https://github.com/intrd/bitcoin/tree/master/hal10k

Must be configured in *configs.php* and started on *hal10k.bat* (remember to edit *hal10k.bat* to correct default path: *C:\webserver\www\hal10k*)   

This project was developed in the environment described below, so please try to use a similar configuration:

* XAMPP version 1.7.7 (not included in the project)
** Apache 2.2.21 (running as service to avoid strange problems in pChart lib)   
** PHP 5.3.8 (VC9 X86 32bit thread safe) + PEAR   
* Libs utilizadas (included in the project)
** pChart 2.1.3 for graphics generation;  
** tmhOAuth for Twitter notifications;  

####TodoList
* Add EUR 
- Fake USD/EUR balance
- Implement Vircurex, BTC-e and Bitstamp API
- Create a version of .bat Windows looping file for Linux using Shell script+crontab 
- Auto fetch BitcoinCharts API data

####Changelog
* v2.4 beta 1 **(not at stable public release yet)**
   * php builtin webserver instead apache
   * added pivots(support & resistance) analysis
   * added MACD analysis
   * JS Graphic chart Client Side rendering
   * Now fetching past history data before starting bot (works on backtests, live & paper)
* v2.3 beta 1 **(not at stable public release yet)**
   * Fixed critical error at Market Direction decision (always returning MA, not EMA)
* v2.2 beta 1
   * Built-in apache+php pre-configured (1-click-to-run version for Windows)
   * Some EMA improvements
   * Better graphic chart
   * Massive bug fixes
* v2.1 beta 2
   * New trading technique implemented: EMA short/long crossover
   * Paper trading: Differs to Backtesting, its a simulation with Live Tickers but w/ fake money balance.
   * Improvements in Clear Log file
   * Improvements in en_US translation
   * Improvements in Twitter oauth conf
* v1.0 beta 2
   * First public version


> HAL10K Bitcoin trading bot (CC) copyleft http://dann.com.br - @intrd (Danilo Salles) <contact@dann.com.br> HAL10K is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License 2, as published by the Free Software Foundation.

