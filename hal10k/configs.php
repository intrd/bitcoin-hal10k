<?php
/** 
* HAL10K Bitcoin trading bot
* @copyright (C) http://dann.com.br - @intrd (Danilo Salles) <contact@dann.com.br>
*
* HAL10K is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License 2
* as published by the Free Software Foundation.
* 
* HAL10K is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
**/
	header('Content-Type: text/html; charset=utf-8');
	date_default_timezone_set('UTC');
	
	include_once("functions.php");

	$root=dirname(__FILE__);
	$datadir=$root."/data/";

	/* VERSION */
	$version="v2.0 beta 1";

	/* Local data files (No need to change) */
	$settings=$datadir."settings.ini"; //Filename of runtime settings;
	$nextmov=$datadir."next_mov.csv"; //Filename of next move text
	$datachart=$datadir."data.csv"; //Filename of btc-usd pricelog (for chart use)
	$fakegox=$datadir."fakegox.txt"; //Filename of fake ballance data
	$logfile=$datadir."log.txt"; //Filename of detailed log
	$lastfile_clean=$datadir."log_clean.txt"; //Filename of clean log
	$lastfile=$datadir."last.txt"; //Filename of last action control file
	$fake_index=$datadir."fake_index.txt"; //Filename of backtesting index buffer
	
	/* MTGox app data. If you do not have access to the API gox, visit: https://www.mtgox.com/security and create your key (with read/write) */
	$gox["app_id"]="xxxxxxxxxxxxxxxxxxxxxxxx";
	$gox["app_secret"]="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
	$login="intrd";

	/* Custom trading algoritm parameters */
	$percentual=0.45; //Minimum percentage of profit on the purchase (Use the current fee applied by MtGox);
	$bidfee=0.45; //Just for simulation purposes (Use the current fee applied by MtGox);
	$up_diff=10; //(sell) profit points (in USD) above the purchase price;
	$up_diff_inv=5; //(sell) stop loss (in USD) below the purchase price;
	$down_diff=10; //(buy) profit points (in USD) below the selling price;
	$down_diff_inv=5; //(buy) stop loss (in USD) above the selling price;
	$secure_ticker=300.99500; //Security value that prevents the bot to make sales below a certain value;
	$emacross=true; //Turn on EMA crossover method (if emacross=true, Simple Market Direction Method automatically is turned off);
	$emaShort=20; //EMA short period(in minutes because you are using $interval=60;) for EMA crossover method;
	$emaLong=44; //EMA long period(in minutes because you are using $interval=60;) for EMA crossover method;
	$emaDiff=5; //EMA difference between short and long crossover;
	$last_two_orders=false; //Base next move on the last two transactions value;
	$interval=60; //Bot loop interval (in seconds);
	$timeout=80; //Timeout in seconds for completion of the bid/ask;
	$sudden_mode=0; //When active, the bot makes a purchase at the sale price, or a sale at the purchase price. Used only when there is a need for immediate order processing. It will be disabled after the order is processed;
	$reverse_prices=0; //Same as Sudden, but definite. It will never turned off;
	$manualstoploss=0; //When active, in every action stop-loss requests remote confirmation of the bot operator;
	$dire=6; //Amount of past intervals used in the identification of Simple Market Direction Method;
	$dire_limbo=13; //Minimum variation (in USD) to define whether the direction is out of limbo or not (limbo: when the bot still trying to set the direction of the market);
	$vol_limbo=450.78526468; //Minimum volume (in USD) to consider an abnormal change at the volume of an interval to another;
	
	/* Backtesting and Paper trading */
	$fake=true; //Turns on/off simulation (backtesting);
	$paper=false; //Paper trading is simulation with Real Live Tickers but w/ fake money balance. (If Paper trading is true, $fake needs to be defined to "true");
	$fake_btc_balance="0.04161308"; //Initial amount of BTC for the simulation;
	$fake_btc_usd_buyedprice="707.00000"; //Initial purchase price of BTC (ATENTION: you need to set this value for Backtesting and Paper trading);
	$fake_datetime_of_firstbid="2014-01-01 00:00:00"; //Initial datetime (same as first line of $fakegox_tickers file below)
	$fakegox_tickers=$datadir."fakegox_tickers(24.12-26.12).txt"; //Access http://bitcoincharts.com/charts/mtgoxUSD, set the period (must be a period that supports interval 1min), click Raw Data, copy/paste the contents of the table in a TXT file and replaces tabulations by "," comma);
	
	/* Data for accessing the Twitter API, if you do not have go to: https://dev.twitter.com/apps and create your key (with read/write permissions) */
	$twitter_oauth["consumer_key"]="XXXXXXXXXXXXXXXXX";
	$twitter_oauth["consumer_secret"]="XXXXXXXXXXXXXXXXXXXXXXXXXXX";
	$twitter_oauth["token"]="XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
	$twitter_oauth["secret"]="XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
	$twitter_users="@intrd"; //Twitter @users to be notified
	$enable_tweet=false; //enable/disable twitter notifications
	
	/* Format of starting data */	
	$reset_data=true; //turn on to reset data every run;
	if ($fake==true or $reset_data==true){
		$default_data="$fake_datetime_of_firstbid,,$fake_btc_usd_buyedprice,ask,loss,7.71,,,\r\n$fake_datetime_of_firstbid,,$fake_btc_usd_buyedprice,bid,loss,7.71,,,\r\n";
		wfilenew($datachart,$default_data);
		$default_data="bid,[btc$fake_btc_balance/usd:0],$fake_btc_usd_buyedprice,$fake_datetime_of_firstbid,loss,#suddenmode,\r\n";
		wfilenew($lastfile,$default_data);
		$default_data='{"data":{"Login":"'.$login.'","Trade_Fee":'.$percentual.',"Wallets":{"BTC":{"Balance":{"value":'.$fake_btc_balance.'}},"USD":{"Balance":{"value":0}}}},"trade_mode":"bid","balancing":0}';
		wfilenew($fakegox,$default_data);
		$default_data="0";
		wfilenew($fake_index,$default_data);
		$default_data="Starting HAL10K with $fake_btc_balance BTC buyd @ $fake_btc_usd_buyedprice\r\n";
		wfilenew($lastfile_clean,$default_data);
	}

echo "\r\n### Access hal10k/index.php on your browser to open the HTTP Control Panel."; //COMMENT THIS LINE AT FIRST RUN 
echo "\r\n### First running? edit these files: configs.php e o hal10k.bat, and comment '//' this line."; sleep(100); //COMMENT THIS LINE AT FIRST RUN 
?>
