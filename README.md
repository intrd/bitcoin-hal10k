# HAL 10K - Trading bot

After losing some money on Bitcoin exchanges, I decided to develop my own Trading bot/helper. This bot acts with pre-defined parameters based on statistics and not with the emotion of the moment, so it's much easier to perform trading operations.

**HAL10K** is a Bitcoin trading/helper bot written in PHP (open source). It was developed to be used on the API MtGox, but can be easily adapted to other exchanges. Their decisions are based on customizable parameters and basic techniques for market analysis. It also works in semi-automatic mode, in which each loss(stop-loss) asks via Twitter for remote confirmation of a bot operator. Besides the "Live Trading" so it also runs simulations "Backtesting" using "Fake Balance" and historical raw data from **BitcoinCharts.com**. All bot actions(buying/selling) and alerts(high volume detected) are notified via Twitter. Log results is accompanied by HTTP control where the bot displays its own performance chart panel.

More details at: [github.com/intrd/bitcoin/tree/master/hal10k](https://github.com/intrd/bitcoin/tree/master/hal10k/)
