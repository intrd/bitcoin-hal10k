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
    
function getset($sett){
	global $settings;
	if (file_exists($settings)){
		$F1=file($settings);
		$data=json_decode($F1[0]);
		$data = (array) $data;
		return $data[$sett]; 
	}
}

function setset($set,$value){
	global $settings;
	if (file_exists($settings)){
		$F1=file($settings);
		$data=json_decode($F1[0]);
		$data = (array) $data;
		$data[$set]=$value;
		$data=json_encode($data);	
		wfilenew($settings,$data);
	}
}

function vol_anormal($last_minutes){
	global $datachart;
	global $vol_limbo;
	$F1=file($datachart);
	$total=(count($F1)-1);
	$c=0;
	while ($c<=$last_minutes){
		if (isset($F1[($total-$c)])){
			$valu=$F1[($total-$c)];
			$valu=explode(",",$valu);
			$valu=$valu[5];
			$values[]=$valu;
		}
		$c++;
	}
	$values=array_reverse($values);

	$data["min"]=min($values);
	$data["end"]=end($values);
	$ddii=($data["end"]-$data["min"]);
	if (abs($data["end"]-$data["min"])>$vol_limbo){
		return $ddii;
	}
	return false;
}

function lastnprices($arra,$nval){
	$lastn = array_slice($arra, -$nval); 
	$c=0;
	foreach ($lastn as $value){
		$value=explode(",",$value);
		if (strlen($value[2])>=2){
			$value=$value[2];
			$value=round($value,2);
			$values[$c]["cur"]=$value;
			//$values[$c]["emaShort"]=$value;
		}
		if (strlen($value[6])>=2){
			$value=$value[6];
			$value=round($value,2);
			$values[$c]["emaShort"]=$value;
		}
		if (strlen($value[7])>=2){		
			$value=$value[7];
			$value=round($value,2);
			$values[$c]["emaShort"]=$value;
		}
		$c++;
	}
	return $values;
}

function emarket_direction(){
	global $datachart;
	global $dire_limbo;
	global $emaShort;
	global $emaLong;
	
	$F1=file($datachart);
	$total=(count($F1)-1);
	
	if ($total>=$emaLong){
	
		$emaValues=lastnprices($F1,$emaShort);
		$lastValue=end($emaValues);
		foreach ($emaValues as $value){
			$prices[]=$value["cur"];
		}
		if (!isset($lastValue["emaShort"])) $lastValue["emaShort"]=intrd_ma($prices,$emaShort);
		$ema["short"]=round(intrd_ema($lastValue["cur"],$lastValue["emaShort"],$emaShort),2);
		
		$emaValues=lastnprices($F1,$emaLong);
		$lastValue=end($emaValues);
		foreach ($emaValues as $value){
			$prices[]=$value["cur"];
		}
		if (!isset($lastValue["emaLong"])) $lastValue["emaLong"]=intrd_ma($prices,$emaLong);
		$ema["long"]=round(intrd_ema($lastValue["cur"],$lastValue["emaLong"],$emaLong),2);
		
	}
	if (isset($ema)) { return $ema; } else { return false; }
	
}

function market_direction($last_minutes,$limbo=false,$ema=false){
	if ($ema) return $ema;
	global $datachart;
	global $dire_limbo;
	$F1=file($datachart);
	$total=(count($F1)-1);
		$c=0;
		while ($c<=$last_minutes){
			if (isset($F1[($total-$c)])){
				$valu=$F1[($total-$c)];
				$valu=explode(",",$valu);
				$valu=$valu[2];
				$values[]=$valu;
			}
			$c++;
		}
		$values=array_reverse($values);

		$data["min"]=min($values);
		$data["end"]=end($values);
		if ($data["end"]>$data["min"]){
			$data["direction"]="up";
			$data["min"]=min($values);
		}else{
			$data["direction"]="down";
			$data["min"]=max($values);
		}
		$ddii=($data["end"]-$data["min"]);
		//echo "\n\n".abs($ddii)."\n\n";
		if (abs($ddii)<$dire_limbo and $limbo==true) $data["direction"]=$data["direction"].".li";

		return $data["direction"];
}

function tweet($tmhOAuth,$text){
	$code = $tmhOAuth->user_request(array(
	  'method' => 'POST',
	  'url' => $tmhOAuth->url('1.1/statuses/update'),
	  'params' => array(
	    'status' => $text
	  )
	));
	var_dump($code);
	sleep(10);

}

function status($price,$next_action,$next_price,$stoploss,$last_action,$last_price,$direction,$last_prem){
	global $last_two_orders;
	if ($last_two_orders==true) { 
		$last_order=get_lasttrade_local($lst);
		$last_action=$last_order["type"];
		$last_price=$last_order["price"];
		$last_prem=$last_order["prem"];
	}
	
	$next_price=round($next_price);
	$last_price=round($last_price);
	$stoploss=round($stoploss);
	$nm="\n$ $price $direction $next_action @ $next_price stoploss: $stoploss (last: $last_action @ $last_price [$last_prem])";
	echo $nm;
	return $nm;
}
function bid($estimate,$buyprice,$last_order,$wall=0,$balancing=0,$sudden_mode=0){
	global $fake;
	$last_order=order_Add("bid",$estimate,$buyprice,$last_order["price"],$last_order["amount"],$fake);
	$last_order["sudden_mode"]=0;
	if ($last_order==false) return false; 
	echo "*** Processing order...";
	global $fake;
	$myorders = get_orders($fake);
	$c=0;
	global $timeout;
	while (count($myorders["data"])>=1 and $c<$timeout){
		echo ".".$c;
		$myorders = get_orders($fake);
		echo "(".count($myorders["data"]).")";
		sleep(2);
		$c++;
		if ($c>=$timeout){
			cli_beep();cli_beep();
			$cancel=order_cancel($last_order["success"]);
			echo "\n*** Order timeoout! canceled..";
			$last_order["status"]="cancelled";
			global $fake;
			$infodata=get_infodataf($fake); $info=get_infodata($infodata,$fake);
			if ($info['btc_balance']>0.01){
				echo "\n*** WARNING: transacao processada sim!";
				$last_order["status"]="notbalanced";
			}
		}
	}
	if ($c<$timeout or $last_order["status"]=="notbalanced") {
		$last_order["sudden_mode"]=0;
		if (isset($last_order["status"]) and $last_order["status"]=="notbalanced"){
			$last_order["sudden_mode"]=1;
		}
		$last_order["status"]="processed";
		echo " done!";
	}else{
		if ($sudden_mode==1){
			$last_order["sudden_mode"]=1;
		}
	}
	return $last_order;
}

function ask($info,$sellprice,$last_order,$wall=0,$balancing=0,$sudden_mode=0){
	global $fake;
	$last_order=order_Add("ask",$info["btc_balance"],$sellprice,$last_order["price"],$last_order["amount"],$fake);
	$last_order["sudden_mode"]=0;
	if ($last_order==false) return false; 
	echo "*** Processing order...";
	global $fake;
	$myorders = get_orders($fake);
	$c=0;
	global $timeout;
	while (count($myorders["data"])>=1 and $c<$timeout){
		echo ".".$c;
		$myorders = get_orders($fake);
		echo "(".count($myorders["data"]).")";
		sleep(2);
		$c++;
		if ($c>=$timeout){
			cli_beep();cli_beep();
			$cancel=order_cancel($last_order["success"]);
			echo "\n*** Order timeoout! canceled..";
			$last_order["status"]="cancelled";
			global $fake;
			$infodata=get_infodataf($fake); $info=get_infodata($infodata,$fake);
			if ($info['usd_balance'] > 10){
				echo "\n*** WARNING: transacao processada sim!";
				$last_order["status"]="notbalanced";
			}
		}
	}
	if ($c<$timeout or $last_order["status"]=="notbalanced") {
		$last_order["sudden_mode"]=0;
		if ($last_order["status"]=="notbalanced"){
			$last_order["sudden_mode"]=1;
		}
		$last_order["status"]="processed";
	}else{
		if ($sudden_mode==1){
			$last_order["sudden_mode"]=1;
		}
	}
	return $last_order;
}

function cli_beep(){
    echo "\x07";
}

function get_lasttrade_local($pen=false){
	global $lastfile;
	$F1=file($lastfile);
	$c=count($F1)-1;
	//echo $c;
	//die;
	if ($pen==true and $c>=1) { 
		$F1=$F1[$c-1]; 
		//var_dump($F1);
		//die;
	}else{
		$F1=$F1[$c]; 
	}
	$F1=explode(",",$F1);
	$last_order["type"] = $F1[0]; 
	$last_order["amount"] = $F1[1]; 
	$last_order["price"] = $F1[2]; 
	$last_order["datetime"] = $F1[3];
	$last_order["prem"] = $F1[4];
	$last_order["prem_value"] = $F1[5];
	return $last_order;
}

function intrd_ma($prices,$periods){
		$maBuffer = array_slice($prices, -$periods);
	 	$ma=(array_sum($maBuffer)/$periods);
	 	return $ma;
}

function intrd_ema($curr_price,$lastema,$periods){
	//EMA = Price(t) * k + EMA(y) * (1 – k)
	//t = current period, y = last period, N = number of periods, k = 2/(N+1)
	$t=$curr_price;
	$k=(2/(1+$periods));
	$y=$lastema;
	$N=$periods;
	$ema = $t * $k + $y * (1-$k);
	//$ema= ( ($curr_price * $multi) + ( $lastema*(1-$multi) ) );
	if ($ema==false or $ema=="") $ema=$lastema;
	return $ema;
}

function percentual($perc,$value){
	$value=($value*$perc/100);
	return $value;
}

function wfilenew($file,$linha){ 
	$fi = fopen($file, "w");
    fwrite($fi, $linha);
    fclose($fi);
}

function wfile($file,$linha){ 
	$fi = fopen($file, "a");
    fwrite($fi, "\r\n".$linha);
    fclose($fi);
}

function wfilew($file,$linha){ 
	$fi = fopen($file, "a");
    fwrite($fi, $linha);
    fclose($fi);
}

function get_btcbyusd($usd,$btcprice){
	$value['bruto']=($usd/$btcprice);
	global $percentual;
	$value['liquido']=($value['bruto']-percentual($percentual,$value['bruto'])); 
	return $value;
}

function get_usdbybtc($btc,$btcprice){
	$value=($btcprice*$btc);
	return $value;
}

function get_lasttrade($trades,$type){
	foreach($trades as $trade){
		if ($trade["Type"]==$type){
			return $trade;
		}
	}
}

function get_infodata($infodata,$fake=false,$replace=false){
	if ($fake) {
		global $fakegox;
		if ($replace) unlink($fakegox);
		if (file_exists($fakegox)){
			$F2=file($fakegox);
			$F2=$F2[0];
			$data=json_decode($F2);
			$infodata = json_decode(json_encode($data), true);
			unset($data);
			$fake=false;
		}
	}
	$logged = $infodata["data"]["Login"];
	$trade_fee = $infodata["data"]["Trade_Fee"];
	$btc_balance = $infodata["data"]["Wallets"]["BTC"]["Balance"]["value"];
	$usd_balance = $infodata["data"]["Wallets"]["USD"]["Balance"]["value"];
	$balancing=0;
	if ($btc_balance>0.01 and $usd_balance > 10){
		echo "\n*** WARNING: USD and BTC are balanced.";
		$balancing=1;
		$last_order=get_lasttrade_local(false); //repete a ordem anterior pra tentar desbalancear
		if ($last_order["type"]=="ask"){
			$trade_mode="ask";
		}else{
			$trade_mode="bid";
		}	
	}else{
		if ($btc_balance>0.01){
			$trade_mode="ask";
		}else{
			$trade_mode="bid";
		}
		if ($usd_balance>10){
			$trade_mode="bid";
		}else{
			$trade_mode="ask";
		}
	}
	$data["logged"]=$logged;
	$data["trade_fee"]=$trade_fee;
	$data["btc_balance"]=$btc_balance;
	$data["usd_balance"]=$usd_balance;
	$data["trade_mode"]=$trade_mode;
	$data["balancing"]=$balancing;
	if ($fake) {
		global $fakegox;
		if (!file_exists($fakegox)){
			$fakedata["data"]["Login"]=$data["logged"];
			$fakedata["data"]["Trade_Fee"]=$data["trade_fee"];
			$fakedata["data"]["Wallets"]["BTC"]["Balance"]["value"]=$data["btc_balance"];
			$fakedata["data"]["Wallets"]["USD"]["Balance"]["value"]=$data["usd_balance"];
			$fakedata["trade_mode"]=$data["trade_mode"];
			$fakedata["balancing"]=$data["balancing"];
			$fakedata=json_encode($fakedata);
			wfilenew($fakegox,$fakedata);
		}
	}
	return $data;
}

function conte_numbers($var){
	return preg_match('/[0-9]/', $var);
}

function load_btccharts_data($file){
	global $fake_index;
	if (file_exists($fake_index)){
		$findex=file($fake_index);
		$index=trim($findex[0]);
	}else{
		$index=0;
	}
	if ($index>=count(file($file))){
		echo "\r\n *** Simulation is over! *** \r\n";
		sleep(900);
		die;
	}
	$F2=file($file);
	$F2=trim($F2[$index]);
	while ((strpos($F2,"+")!==false) or (strpos($F2,"Infinity")!==false)){
		$index++;
		if ($index>=count(file($file))){
			echo "\r\rnsimulation is over!\r\n";
			sleep(900);
			die;
		}
		$F2=file($file);
		$F2=trim($F2[$index]);
	}
	$F2=explode(",",$F2);

	$data["datetime"]=$F2[0];
	$data["bid"]=$F2[3];
	$data["ask"]=$F2[2];
	$data["high"]=$F2[2];
	$data["low"]=$F2[3];
	$data["btc_volume"]=$F2[5];
	$data["usd_volume"]=$F2[6];
	$data["weighted_price"]=$F2[7];
	$data["close"]=$F2[4];
	$data["open"]=$F2[1];
	$index++;
	if ($index>=count(file($file))){
		echo "\r\rnsimulation is over!\r\n";
		sleep(900);
		die;
	}
	wfilenew($fake_index,$index);
	return $data;
}

function get_ticker($ticker,$fake=false){
	global $paper;
	if ($paper) $fake=false;
	if ($fake) {
		global $fakegox_tickers;
		if (file_exists($fakegox_tickers)){
			$data=load_btccharts_data($fakegox_tickers);
			$ticker["data"]["datetime"]["value"]=$data["datetime"];
			$ticker["data"]["high"]["value"]=$data["high"];
			$ticker["data"]["low"]["value"]=$data["low"];
			$ticker["data"]["avg"]["value"]=$data["weighted_price"];
			$ticker["data"]["buy"]["value"]=$data["bid"];
			$ticker["data"]["sell"]["value"]=$data["ask"];
			$ticker["data"]["vol"]["value"]=$data["usd_volume"];
			$ticker["data"]["last"]["value"]=$data["close"];
			unset($data);
			//$fake=false;
		}
	}
	$ticker_last=$ticker["data"]["last"]["value"];
	$ticker_high=$ticker["data"]["high"]["value"];
	$ticker_low=$ticker["data"]["low"]["value"];
	$ticker_avg=$ticker["data"]["avg"]["value"];
	$ticker_buy=$ticker["data"]["buy"]["value"];
	$ticker_sell=$ticker["data"]["sell"]["value"];
	$ticker_vol=$ticker["data"]["vol"]["value"];
	$data["ticker_last"]=$ticker_last;
	$data["ticker_high"]=$ticker_high;
	$data["ticker_low"]=$ticker_low;
	$data["ticker_avg"]=$ticker_avg;
	$data["ticker_buy"]=$ticker_buy;
	$data["ticker_sell"]=$ticker_sell;
	$data["ticker_vol"]=$ticker_vol;
	if ($fake) $data["datetime"]=$ticker["data"]["datetime"]["value"];
	$fake=false;
	return $data;
}

//fake
function localOrderAdd($type,$amount,$price){
	global $percentual;
	global $fake;
	global $bidfee;
	$infodata=get_infodataf($fake); $info=get_infodata($infodata,$fake);
	$ok=0;

	$amount=($amount*0.00000001);
	$price=($price*0.00001);

	if ($type=="ask" and round($info["btc_balance"],2)>=round($amount,2)){
		$info["usd_balance"]=($info["usd_balance"]+($amount*$price));
		$info["btc_balance"]=($info["btc_balance"]-$info["btc_balance"]);
		$ok=1;
	} 
	if (($type=="bid") and ((round($info["usd_balance"],2))>=(round($price*$amount,2)))){
		$info["btc_balance"]=($info["btc_balance"]+$amount);
		$info["btc_balance"]=$info["btc_balance"]-percentual($bidfee,$info["btc_balance"]);
		$info["usd_balance"]=($info["usd_balance"]-$info["usd_balance"]);
		$ok=1;
	}

	if ($ok==1){
		$fakedata["data"]["Login"]=$info["logged"];
		$fakedata["data"]["Trade_Fee"]=$info["trade_fee"];
		$fakedata["data"]["Wallets"]["BTC"]["Balance"]["value"]=$info["btc_balance"];
		$fakedata["data"]["Wallets"]["USD"]["Balance"]["value"]=$info["usd_balance"];
		$fakedata["trade_mode"]=$info["trade_mode"];
		$fakedata["balancing"]=$info["balancing"];
		$info=get_infodata($fakedata,$fake=true,$replace=true);
		$result["result"]="success";
		$result["data"]="98374829984767289039847839";
		return $result;
	}else{
		$result["result"]="error";
		return $result;
	}
	//die;
}
function order_Add($type,$amount,$price,$prev,$prev_amount,$fake=false){
	cli_beep();
	$datetime=date("Y-m-d H:i:s");
	echo "\n$ $type ".$amount." @ $".$price." (prev: ".$prev.") - ".$datetime;
	$order["type"] = $type; 
	$order["amount"] = $amount; 
	$order["price"] = $price; 
	$order["datetime"] = $datetime; 
	$amount=($amount/0.00000001);
	$price=($price/0.00001);

	if ($fake==false){
		global $mtGoxClient;
		$result = $mtGoxClient->orderAdd($type,$amount,$price);
		//echo "ERROR DIE!";
		//die;
	}else{
		$result = localOrderAdd($type,$amount,$price);
	}
	$c=0;
	while (!isset($result["result"]) and $c<3) {
		if ($fake==false){
		global $mtGoxClient;
		$result = $mtGoxClient->orderAdd($type,$amount,$price);
		//echo "ERROR DIE!";
		//die;
		}else{
			$result = localOrderAdd($type,$amount,$price);
		}
		$c++;
	}
	if ($result["result"]=="error") {
		var_dump($result);
		return false;
	}
	echo"\n";
	var_dump($result);
	$order["success"] = $result['data']; 
	$order["status"] = $result['data'];

	if ($type=="ask"){
		if ($order["price"]>=$prev){
			$order["prem"] = "profit"; 
		}else{
			$order["prem"] = "loss"; 
		}
	}
	if ($type=="bid"){
		if ($order["price"]<=$prev){
			$order["prem"] = "profit"; 
		}else{
			$order["prem"] = "loss"; 
		}
	}
	return $order;
}

function get_orders($fake=false){
	$fakedata["data"]=NULL;
	if ($fake) return $fakedata;
	global $mtGoxClient;
	$myorders = $mtGoxClient->getOrders();
	$c=0;
	while (!isset($myorders["data"]) and $c<3) {
		$myorders = $mtGoxClient->getOrders();
		$c++;
	}
	return $myorders;
}

function order_cancel($id){
	global $mtGoxClient;
	$result = $mtGoxClient->orderCancel($id);
	$c=0;
	while (!isset($result["data"]) and $c<3) {
		var_dump($result);
		$result = $mtGoxClient->orderCancel($id);
		$c++;
	}
	return $result;
}

//eofake

function read_stdin()
{
        $fr=fopen("php://stdin","r");   
        $input = fgets($fr,128);        
        $input = rtrim($input);         
        fclose ($fr);                   
        return $input;                  
}

function get_infodataf($fake=false){
	if ($fake) return false;
	global $mtGoxClient;
	$infodata = $mtGoxClient->getInfo();
	while (!isset($infodata["data"])) {
		$infodata = $mtGoxClient->getInfo();
	}
	return $infodata;
}

function get_tickerf($fake=false){
	global $paper;
	if ($paper) $fake = false;
	if ($fake) return false;
	global $mtGoxClient;
	$ticker = $mtGoxClient->getTicker(); 
	while (!isset($ticker["data"])) {
		$ticker = $mtGoxClient->getTicker(); 
	}
	return $ticker;
}


?>