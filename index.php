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
    
	echo "<center><h1>HAL 10K</h1></center>";
	$time=date("Y-m-d H:i:s");
	echo "<center><h3>Time: $time</h3></center>";
	echo "<img src='chart.php";echo "'>";
	echo "<center><form action='makesudden.php'  method='post'>";
	echo "Sudden MODE? (ATTENTION! OK?)";
	echo '&nbsp;Password: <input type="text" name="i"><br>';
	echo '&nbsp;Death mode: <input type="text" name="hal"><br><input type="submit" value="Submit">';
	echo "</form></center>";
	echo "<center><b>HAL10K</b> - Bitcoin trading bot</center>";
	echo "<center>Copyright (C) http://dann.com.br - @intrd (Danilo Salles) <contact@dann.com.br></center>";
?>
