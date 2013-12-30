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

	/* Local data files */
	$settings=$datadir."settings.ini"; //Filename of runtime settings;
	$nextmov=$datadir."next_mov.csv"; //Filename of next move text
	$datachart=$datadir."data.csv"; //Filename of btc-usd pricelog (for chart use)
	$fakegox=$datadir."fakegox.txt"; //Filename of fake ballance data
	$logfile=$datadir."log.txt"; //Filename of detailed log
	$lastfile_clean=$datadir."log_clean.txt"; //Filename of clean log
	$lastfile=$datadir."last.txt"; //Filename of last action control file
	$fake_index=$datadir."fake_index.txt"; //Filename of backtesting index buffer
	
	/* MTGox app data, caso ainda não possua acesso a API do gox, acesse: https://www.mtgox.com/security e crie sua chave (com permissões de leitura/escrita) */
	$gox["app_id"]="XXXXXXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX";
	$gox["app_secret"]="XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
	$login="intrd";

	/* Custom trading algoritm parameters */
	$percentual=0.45; //Percentual mínimo de lucro na compra (Use a fee atual aplicada pelo MTGox);
	$up_diff=15; //(venda) pontos de lucro em USD acima do preço de compra;
	$up_diff_inv=5; //(venda) stop de prejuízo em USD abaixo do preço de compra;
	$down_diff=15; //(compra) pontos de lucro em USD abaixo do preço de venda;
	$down_diff_inv=5; //(compra) stop de prejuízo em USD acima do preço de venda;
	$secure_ticker=300.99500; //Valor de segurança que impede o bot de fazer vendas abaixo de um valor determinado;
	$interval=1; //Intervalo do loop do bot (em segundos);
	$timeout=80; //Timeout em sergundos p/ conclusão da compra/venda;
	$sudden_mode=0; //Quando ativo, faz a compra no preço de venda e a venda no preço de compra. Usado apenas quando existe a necessidade de processamento imediato da ordem. Desativado após a ordem ser processada;
	$reverse_prices=0; //O mesmo que o Sudden, porém definitivo. Ele nunca é desativado; 
	$manualstoploss=0; //Quando ativo, em cada ação de stop-loss solicita a confirmação remota de seu operador;
	$dire=6; //Quantidade de intervalos passados usados na identificação da direção de mercado;
	$dire_limbo=13; //Variação mínima em USD para definir se a direção já saiu ou nao do lcfirst(str)imbo(momento em que o bot ainda não conseguiu definir uma direção);
	$vol_limbo=150.78526468; //Volume mínimo para considerar uma alteração anormal no volume de um intervalo para outro;
	
	/* Backtesting */
	$fake=true; //Liga e desliga a simulação (Backtesting);
	$fake_btc_balance="1.03161308"; //Quantidade inicial de BTC para a simulação;
	$fake_btc_usd_buyedprice="707.00000"; //Preço inicial de compra do BTC fake balance;
	$fake_datetime_of_firstbid="2013-12-21 00:01:00";
	$fakegox_tickers=$datadir."fakegox_tickers(27.12).txt"; //Acesse http://bitcoincharts.com/charts/mtgoxUSD, defina o período, precisa ser um periodo que suporte intervalo de 1min, clique em Raw Data (copie/cole o conteúdo da tabela num txt e substituia as tabulações por "," vírgula); 
	
	/* dados para acesso a API do Twitter, caso não possua, acesse: https://dev.twitter.com/apps e crie sua chave (com permissões de leitura/escrita) */
	$twitter_oauth["consumer_key"]="XXXXXXXXXXXXXXXXXXX";
	$twitter_oauth["consumer_secret"]="XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
	$twitter_oauth["token"]="XXXXXXXXXX-XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
	$twitter_oauth["secret"]="XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
	$twitter_users="@intrd";
	$enable_tweet=false;
	
	/* Dados de start p/ a simulação, NÃO ALTERE */
	if ($fake){		
		$default_data="$fake_datetime_of_firstbid,,$fake_btc_usd_buyedprice,bid,preju,7.71,\r\n";
		wfilenew($datachart,$default_data);
		$default_data="bid,[btc$fake_btc_balance/usd:0],$fake_btc_usd_buyedprice,2013-12-27 12:55:55,preju,#suddenmode,\r\n";
		wfilenew($lastfile,$default_data);
		$default_data='{"data":{"Login":"'.$login.'","Trade_Fee":'.$percentual.',"Wallets":{"BTC":{"Balance":{"value":'.$fake_btc_balance.'}},"USD":{"Balance":{"value":0}}}},"trade_mode":"bid","balancing":0}';
		wfilenew($fakegox,$default_data);
		$default_data="0";
		wfilenew($fake_index,$default_data);
		$default_data="Starting HAL10K with $fake_btc_balance BTC buyd @ $fake_btc_usd_buyedprice\r\n";
		wfilenew($lastfile_clean,$default_data);
	}

//echo "EDITE OS ARQUIVOS: configs.php e o hal10k.bat"; //REMOVA APÓS EDITAR ESTE CONFIG FILE PELA PRIMEIRA VEZ 
//sleep(100); //REMOVA APÓS EDITAR ESTE CONFIG FILE PELA PRIMEIRA VEZ
//die; //REMOVA APÓS EDITAR ESTE CONFIG FILE PELA PRIMEIRA VEZ

?>
