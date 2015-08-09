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

include("configs.php");
require_once("MtGoxClient.php");

if (isset($_SERVER["REMOTE_ADDR"])) { die; };

require $root."/twitter_api/tmhOAuthExamples-master/tmhOAuthExample.php"; $tmhOAuth = new tmhOAuthExample();
$mtGoxClient = new MtGoxClient($gox["app_id"],$gox["app_secret"]);

$replace=false;
$infodata=get_infodataf($fake); $info=get_infodata($infodata,$fake,$replace);
$ticker=get_tickerf($fake); /*var_dump($ticker);*/ $ticker=get_ticker($ticker,$fake);

//var_dump($ticker);
//die;

echo "\n*** HAL_10K $version (by intrd)";
echo "\n$ Logged: ".$info["logged"];
echo " ( BTC: ".$info["btc_balance"];
echo " / USD: ".$info["usd_balance"]." )";
echo "\n# Trading - fee: ".$info["trade_fee"]." - ( USD range down: $up_diff / up: $up_diff ) Interval secs: $interval";
echo "\n# Ticker 24h -> High: ".$ticker["ticker_high"]." / Low: ".$ticker["ticker_low"]." / Avg: ".$ticker["ticker_avg"]." )";
wfile($lastfile_clean,$datetime=date("d/m/Y h:i:s")." - ( BTC: ".$info["btc_balance"]." / USD: ".$info["usd_balance"]." )");

echo "\n# Loop starting.. ";
while (1==1){
	$kills=getset('kill');
	if ($kills==1){
		echo " #KILL!";
		sleep(100);
		die;
	} 
	$sudden_mode=getset('sudden');
	if ($sudden_mode==1){
		echo "\r\n### MANUAL SUDDEN ###\r\n";
	}
	$infodata=get_infodataf($fake); $info=get_infodata($infodata,$fake);

	$dt=date("Y-m-d H:i:s");
	$hora_new=date("i");
	$ticker=get_tickerf($fake); $ticker=get_ticker($ticker,$fake);
	$vol=$ticker["ticker_vol"];
	if ($fake==true and $paper==false) $dt=$ticker["datetime"];
	
	//if (isset($ema)) unset($ema);
	if ($emacross==true) { 
		$lastema=emarket_direction(); 
		echo "\r\n*** EMAShort".$lastema["short"];
		echo " / EMALong".$lastema["long"]."";
		if ($lastema["short"]>$lastema["long"]) {
			if (($lastema["short"]-$lastema["long"])>$emaDiff){
				$ema="up";
			}else{
				$ema="up.limbo";
			}
		}else{
			if (($lastema["long"]-$lastema["short"])>$emaDiff){
				$ema="down";
			}else{
				$ema="down.limbo";
			}
		}
		if ($lastema==false){
			$ema="limbo";
		}
	}
	//echo $ema;
	//die;
	//if ($emacross==true and !isset($ema)) {
	//	$ema="limbo";
	//}

	$line=$dt.",,".$ticker["ticker_last"].",,,$vol,".$lastema["short"].",".$lastema["long"].",\r\n";
	$F1=file($datachart);
	//echo $ticker["ticker_last"];
	$hora=end($F1); $hora=explode(",",$hora);
 	$times=date('i', strtotime($hora[0]));
 	$hora_old=$times;

	wfilew($datachart,$line);
	$voll=vol_anormal(1);
	if ($voll!=false) {
			echo "\n*** High volume detected! ".$voll;
			if ($enable_tweet) tweet($tmhOAuth,"High volume detected! ".$voll." $twitter_users");
	}
	

	$last_order=get_lasttrade_local($last_two_orders);
	$ticker=get_tickerf($fake); $ticker=get_ticker($ticker,$fake);
	
	if (1==1){
		echo "\n*** Checking for open orders... ";
		$myorders = get_orders($fake);
		if (count($myorders["data"])>=1) {echo "waiting ".count($myorders["data"])." orders to process.";}else{ echo" no orders.";
			$transa=false;
			$wall=false;
			if ($info["trade_mode"]=="ask"){
				if ($reverse_prices==1) $ticker["ticker_sell"]=$ticker["ticker_buy"];
				$range=($ticker["ticker_sell"]-($last_order["price"]+$up_diff));
				//$updown=
				$direction="<".market_direction($dire,true,$ema)."/down>";
				$next_price=($last_order["price"]+$up_diff);
				$stoploss=($last_order["price"]-$up_diff_inv);
				$nm=status($ticker["ticker_sell"],$info["trade_mode"],$next_price,$stoploss,$last_order["type"],$last_order["price"],$direction,$last_order["prem"]);
				wfilenew($nextmov,$nm);
				if (($ticker["ticker_sell"]>=$next_price and market_direction($dire,true,$ema)=="down") or ($ticker["ticker_sell"]<=$stoploss and market_direction($dire,false,$ema)=="down") or $sudden_mode==1){
					if (($ticker["ticker_sell"]<=$stoploss and market_direction($dire,false,$ema)=="down")){
						$wall=1;
						if ($manualstoploss==0) $sudden_mode=1;
						echo "\n*** #Hit wall!";
					}
					if ($sudden_mode==1){
						$sudden_mode=1;
						$wall=false;
						$ticker["ticker_sell"]=$ticker["ticker_buy"];
						echo "\n*** #SUDDEN MODE on!";
					}
					global $secure_ticker;
					if ($ticker["ticker_sell"]<$secure_ticker) {
						echo "\n\n### FATAL ERROR - check secure ticker ###\n\n"; 
						sleep(500);
					}
					if ($manualstoploss==1 and $wall==1){
						echo "\n*** Skipped automatic stoploss action";
					}else{
						$infodata=get_infodataf($fake); $info=get_infodata($infodata,$fake);
						$transa=ask($info,$ticker["ticker_sell"],$last_order,$wall,$info["balancing"],$sudden_mode);
					}
				}
			} else if ($info["trade_mode"]=="bid"){
				if ($reverse_prices==1) $ticker["ticker_buy"]=$ticker["ticker_sell"];
				$range=((($last_order["price"]-percentual($percentual,$last_order["price"]))-$down_diff)-$ticker["ticker_buy"]);
				$direction="<".market_direction($dire,true,$ema)."/up>";
				$next_price=(($last_order["price"]-percentual($percentual,$last_order["price"]))-$down_diff);
				$stoploss=(($last_order["price"]+percentual($percentual,$last_order["price"]))+$down_diff_inv);
				$nm=status($ticker["ticker_buy"],$info["trade_mode"],$next_price,$stoploss,$last_order["type"],$last_order["price"],$direction,$last_order["prem"]);
				wfilenew($nextmov,$nm);
				if (($ticker["ticker_buy"]<=$next_price and market_direction($dire,true,$ema)=="up") or ($ticker["ticker_buy"]>=$stoploss and market_direction($dire,false,$ema)=="up") or $sudden_mode==1){
					if (($ticker["ticker_buy"]>=$stoploss and market_direction($dire,false,$ema)=="up")){
						echo "\n*** #Hit wall!";
						$wall=1;
						if ($manualstoploss==0) $sudden_mode=1;
					}
					if ($sudden_mode==1){
						$wall=false;
						echo "\n*** #SUDDEN MODE on!";
						$sudden_mode=1;
						$ticker["ticker_buy"]=$ticker["ticker_sell"];
					}
					if ($manualstoploss==1 and $wall==1){
						echo "\n*** Skipped automatic stoploss action";
					}else{
						$infodata=get_infodataf($fake); $info=get_infodata($infodata,$fake);
						$estimate=get_btcbyusd($info["usd_balance"],$ticker["ticker_buy"]);
						$transa=bid($estimate["bruto"],$ticker["ticker_buy"],$last_order,$wall,$info["balancing"],$sudden_mode); 
					}
				}
			}
			if (($wall==1 and $manualstoploss==1) and (getset('tweeted')!=$last_order["price"])){
				$line=file($nextmov);
				$line=$line[1];
				if ($enable_tweet) tweet($tmhOAuth,"Help? $twitter_users ".$line);
				setset("tweeted",$last_order["price"]);
			}
			if ($transa!=false and $transa["status"]!="cancelled"){
				var_dump($transa);
				$balancing=$info["balancing"];
				if ($transa["prem"]=="profit"){
					cli_beep();cli_beep();cli_beep();sleep(1);cli_beep();cli_beep();cli_beep();
				}else{
					cli_beep();sleep(1);cli_beep();sleep(1);cli_beep();
				}
				$log="#transaction";
				if ($sudden_mode==1) $log="#suddenmode";
				if ($balancing==1) $log="#unbalancing";
				$infodata=get_infodataf($fake); $info=get_infodata($infodata,$fake);
				$wallet_amount="[btc:".round($info["btc_balance"],4)."/usd:".round($info["usd_balance"],2)."]";
				$line="$".$transa["type"]." ".$wallet_amount." @ $".$transa["price"]." (".$transa["prem"].") $log"; 
				wfile($lastfile_clean,$line);
				
				if ($balancing!=1){
					if ($enable_tweet) tweet($tmhOAuth,$line." $twitter_users");
					if ($wall==1) {
						wfilew($lastfile,$transa["type"].",".$wallet_amount.",".$transa["price"].",".$transa["datetime"].",".$transa["prem"].",$log,\r\n");
					}else{
						wfilew($lastfile,$transa["type"].",".$wallet_amount.",".$transa["price"].",".$transa["datetime"].",".$transa["prem"].",$log,\r\n");
					}
					$dt=date("Y-m-d H:i:s");
					if ($fake==true and $paper==false) $dt=$ticker["datetime"];
					$hora_new=date("i");
					$pre=$transa["prem"];
					$vol=$ticker["ticker_vol"];
					$type=$transa["type"];
					$line=$dt.",,".$transa["price"].",$type,$pre,$vol,".$lastema["short"].",".$lastema["long"].",\r\n";
					wfilew($datachart,$line);
				}
				if ($transa["sudden_mode"]==1){
					$sudden_mode=1;
					setset("sudden",'1');
					echo "\n*** #SUDDEN MODE stills..";
				}else{
					$sudden_mode=0;
					setset("sudden",'0');
				}
			}
		}
	}
	if ($fake==false) sleep($interval);
	if ($paper==true) sleep($interval);
}



?>