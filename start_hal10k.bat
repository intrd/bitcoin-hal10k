@echo off
REM /**
REM  * HAL10K - Bitcoin PHP Trading bot
REM  * 
REM  * After losing some money on Bitcoin exchanges, I decided to develop my own Trading bot/helper. 
REM  * This bot acts with pre-defined parameters based on statistics/strategies and not with the emotion of the moment, so it's much easier to perform trading operations.
REM  * 
REM * @package HAL10K
REM * @version 2.2
REM * @category bitcoin
REM * @author intrd - http://dann.com.br/
REM * @link https://github.com/intrd/bitcoin-hal10k
REM * @see http://dann.com.br/hal-10k-php-trading-helper-bot/
REM * @copyright 2013 intrd
REM * @license Creative Commons Attribution-ShareAlike 4.0 International License - http://creativecommons.org/licenses/by-sa/4.0/
REM *
REM */

REM start cmd /k Call webserver.bat 4444
echo # At first RUN, please correct the PHP path on start_hal10k.bat or use simply the 1-click-run version: http://dann.com.br/hal10k_1click_apache.php-winx86.zip
pause
:Logit
echo Start time is: %date% %TIME%


cd \xampp\php
php c:\xampp\htdocs\bitcoin\hal10k\start.php

GOTO Logit