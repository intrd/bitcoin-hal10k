<?php
/**
 * HAL10K - Bitcoin PHP Trading bot
 * 
 * After losing some money on Bitcoin exchanges, I decided to develop my own Trading bot/helper. 
 * This bot acts with pre-defined parameters based on statistics/strategies and not with the emotion of the moment, so it's much easier to perform trading operations.
 * 
* @package HAL10K
* @version 2.2
* @category bitcoin
* @author intrd - http://dann.com.br/
* @link https://github.com/intrd/bitcoin-hal10k
* @see http://dann.com.br/hal-10k-php-trading-helper-bot/
* @copyright 2013 intrd
* @license Creative Commons Attribution-ShareAlike 4.0 International License - http://creativecommons.org/licenses/by-sa/4.0/
*
*/
    
	include("configs.php");
	$time=date("i");
	if ($_POST['hal']=="meuovo123654"){
		setset("kill",'1');
	}else{
		echo "#ERROR1&nbsp;";
	}
	if ($_POST['i']==$time){
		echo "SUDDEN DONE.";
		setset("sudden",'1');
	}else{
		echo "#ERROR2";
	}
	
?>
