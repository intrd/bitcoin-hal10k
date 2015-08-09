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

REM start cmd /k Call webserver.bat 4444
echo # At first RUN, please correct the PHP path on start_hal10k.bat or use simply the 1-click-run version: http://dann.com.br/hal10k_1click_apache.php-winx86.zip
pause
:Logit
echo Start time is: %date% %TIME%


cd \xampp\php
php c:\xampp\htdocs\bitcoin\hal10k\start.php

GOTO Logit