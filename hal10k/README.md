# HAL 10K - PHP Trading & helper bot

After losing some money on Bitcoin exchanges, I decided to develop my own Trading bot/helper. This bot acts with pre-defined parameters based on statistics/strategies and not with the emotion of the moment, so it's much easier to perform trading operations.

**HAL10K** is a Bitcoin trading/helper bot written in PHP (open source). It was developed to be used on the API MtGox, but can be easily adapted to other exchanges. Their decisions are based on customizable parameters and trading techniques for market analysis. It also works in semi-automatic mode, in which each loss(stop-loss) asks via Twitter for remote confirmation of a bot operator. Besides the "Live Trading" so it also runs simulations "Backtesting/Paper trading" using "Fake Balance" and historical raw data from **BitcoinCharts.com**. All bot actions(buying/selling) and alerts(high volume detected) are notified via Twitter. Log results is accompanied by HTTP Control panel where the bot displays its own performance chart panel.

**HAL10K** was developed "from scratch", without taking basis of existing algorithms/trading techniques. This project is a learning exercise on Trading/Economics for me, when started this project my experience was limited in programming and Bitcoin. Yes, I'm already getting real profits with this trading bot, and the idea of ​​Open Source assumed that sharing experiences, together we can greatly improve the algorithm and achieve even greater profits.

##Installing: 1click-to-run (easy)
* Download here: [hal10k_1click.zip](https://mega.nz/#!3YgVFC7S!KJ2S-T2Z9oXtpdL-gOkiFXBNXpbiolqQDZ1hjo58X_M) w/ apache+php pre-configured, just run the: start_hal10k.bat

##Installing: manual (advanced)
**GitHUB stable Source code**: https://github.com/intrd/bitcoin/tree/master/hal10k

Must be configured in *configs.php* and started on *hal10k.bat* (remember to edit *hal10k.bat* to your http server default path: *C:\webserver\www\hal10k*)   

This project was developed in the environment described below, so please try to use a similar configuration
* XAMPP version 1.7.7 (not included in the project)
   * Apache 2.2.21 (running as service to avoid strange problems in pChart lib)   
   * PHP 5.3.8 (VC9 X86 32bit thread safe) + PEAR   

* Libs utilizadas (included in the project)
   * pChart 2.1.3 for graphics generation;  
   * tmhOAuth for Twitter notifications;  

##More details & support
* [Official post -> HAL 10K @ PHP Bitcoin trading & helper bot](http://dann.com.br/hal-10k-php-trading-helper-bot/)
* [Bitcointalk thread](https://bitcointalk.org/index.php?topic=391630)

##TodoList
- Add EUR 
- Fake USD/EUR balance
- Implement Vircurex, BTC-e and Bitstamp API
- Create a version of .bat Windows looping file for Linux using Shell script+crontab 
- Auto fetch BitcoinCharts API data

##Changelog
* v2.4 beta 1 **(not at stable public release yet)**
   * - php builtin webserver instead apache
   * - added pivots(support & resistance) analysis
   * - added MACD analysis
   * - JS Graphic chart Client Side rendering
   * - Now fetching past history data before starting bot (works on backtests, live & paper)
* v2.3 beta 1 **(not at stable public release yet)**
   * - Fixed critical error at Market Direction decision (always returning MA, not EMA)
* v2.2 beta 1
   * - Built-in apache+php pre-configured (1-click-to-run version for Windows)
   * - Some EMA improvements
   * - Better graphic chart
   * - Massive bug fixes
* v2.1 beta 2
   * - New trading technique implemented: EMA short/long crossover
   * - Paper trading: Differs to Backtesting, its a simulation with Live Tickers but w/ fake money balance.
   * - Improvements in Clear Log file
   * - Improvements in en_US translation
   * - Improvements in Twitter oauth conf
* v1.0 beta 2
   * - First public version
