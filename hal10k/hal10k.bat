@echo off
REM HAL10K Bitcoin trading bot
REM @copyright (C) http://dann.com.br - @intrd (Danilo Salles) <contact@dann.com.br>
REM
REM HAL10K is free software; you can redistribute it and/or
REM modify it under the terms of the GNU General Public License 2
REM as published by the Free Software Foundation.
REM 
REM HAL10K is distributed in the hope that it will be useful,
REM but WITHOUT ANY WARRANTY; without even the implied warranty of
REM MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
REM GNU General Public License for more details.

REM echo ! > C:\xampp\htdocs\bitcoin\hal10k\data\log.txt
REM echo ! > C:\xampp\htdocs\bitcoin\hal10k\data\log_clean.txt
REM call :Logit>>C:\xampp\htdocs\bitcoin\hal10k\data\log.txt 2>&1
:Logit
echo Start time is: %date% %TIME%
c:
cd c:\xampp\php
php c:\xampp\htdocs\bitcoin\hal10k\start.php
GOTO Logit