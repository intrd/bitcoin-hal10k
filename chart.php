<?php
 /* CAT:Line chart */ 
 /* pChart library inclusions */ 
 //include("configs.php"); 
 include("functions.php");
 include("pChart/class/pData.class.php"); 
 include("pChart/class/pDraw.class.php"); 
 include("pChart/class/pImage.class.php"); 

 /* Create and populate the pData object */ 
 $MyData = new pData();   
 $F1=file("data/data.csv");
 $c=0;
 $pula=0;
 $total=count($F1);
 //$emaShort=10; //ultimos 10minutos
 //$emaLong=25; //ultimos 21minutos

 $control=(count($F1)*12)/100;
 $control2=(count($F1)*1)/100;
 //$control2=5;
 $pular=false; //liga ou desliga a otimização do gráfico

 foreach ($F1 as $value){
 	if ($pular and $pula<=$control2 and (strpos($value,",bid,")===false and strpos($value,",ask,")===false )){
 		$pula++;
 	}else{
	 	$value=explode(",",$value);
	 	if (strpos($value[2],"+")===false){
	 		if ($c>=$control){
	 			$times=date('G:i@j/m', strtotime($value[0]));
	 			$data["times"][]=$times;
	 			$c=0;
	 		}else{
	 			$data["times"][]="";
	 			$c++;
	 		}
	 		
	 		$data["prices"][]=round($value[2]);
			
			/*$total=count($data["prices"]);
			if ($total>=40){
				$lastn = array_slice($data["prices"], -20); 
				if (!isset($emaShortValue)) $emaShortValue=intrd_ma($lastn,20);
				$emaShortValue=intrd_ema(end($lastn),$emaShortValue,20);
				$data["emaShort"][]=$emaShortValue;
				
				$lastn = array_slice($data["prices"], -44); 
				if (!isset($emaLongValue)) $emaLongValue=intrd_ma($lastn,44);
				$emaLongValue=intrd_ema(end($lastn),$emaLongValue,44);
				$data["emaLong"][]=$emaLongValue;
				
			} else{
				$data["emaShort"][]=VOID;
				$data["emaLong"][]=VOID;
			}*/
			
			
			
			
	 		if (strlen($value[6]>=2)){
	 			$data["emaShort"][]=$value[6];
	 		}else{
	 			$data["emaShort"][]=round($value[2]);
	 		}
	 		if (strlen($value[7]>=2)){
	 			$data["emaLong"][]=$value[7];
	 		}else{
	 			$data["emaLong"][]=round($value[2]);
	 		}
			
			
	 		
	 		if ($value[3]!=""){
		 		if ($value[3]=="bid"){
		 			$data["bids"][]=round($value[2]);
		 		}
		 		if ($value[3]=="ask"){
		 			$data["asks"][]=round($value[2]);
		 		}
	 		}else{
	 			$data["bids"][]=VOID;
	 			$data["asks"][]=VOID;
	 		}
	 	}
	 	if ($pular) $pula=0;
	}
 }


 $startdate=$F1[0];
 $startdate=explode(",",$startdate);
 $times=date('G:i@j/m', strtotime($startdate[0]));
 $startdate=$times;
 $enddate=end($F1);
 $enddate=explode(",",$enddate);
 $times=date('G:i@j/m', strtotime($enddate[0]));
 $enddate=$times;

 $F2=file("data/next_mov.csv");
 $nextmov=$F2[1];

 $MyData->setAxisName(0,"Prices"); 
 $MyData->addPoints($data["bids"],"Buy");
 $MyData->addPoints($data["asks"],"Sell");
 $MyData->addPoints($data["prices"],"BTC/USD"); 
 $MyData->addPoints($data["emaShort"],"EMAshort");  
 $MyData->addPoints($data["emaLong"],"EMAlong");  

 $MyData->setSerieShape("Buy",SERIE_SHAPE_FILLEDTRIANGLE);  
 $MyData->setSerieWeight("Buy",2); 
 $MyData->setSerieShape("Sell",SERIE_SHAPE_FILLEDSQUARE); 

 $MyData->addPoints($data["times"],"Labels"); 
 $MyData->setSerieDescription("Labels","Months"); 
 $MyData->setAbscissa("Labels"); 

 $myPicture = new pImage(1400,460,$MyData); 

 $myPicture->Antialias = FALSE; 
 
 $myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>8,"R"=>0,"G"=>0,"B"=>0)); 
 $myPicture->drawText(15,22,"HAL10K by intrd",array("FontSize"=>15,"Align"=>TEXT_ALIGN_BOTTOMLEFT)); 

 //$myPicture->drawText(15,22,"HAL10K by intrd",array("FontSize"=>15,"Align"=>TEXT_ALIGN_BOTTOMLEFT)); 

 $myPicture->drawText(450,20,"Período: ".$startdate." - ".$enddate."",array("FontSize"=>13,"Align"=>TEXT_ALIGN_BOTTOMLEFT));

 $myPicture->drawText(651,23,$nextmov,array("FontSize"=>13,"Align"=>TEXT_ALIGN_BOTTOMLEFT)); 
 
 $myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>10,"R"=>0,"G"=>0,"B"=>0)); 

 $myPicture->setGraphArea(20*2,20,650*2,200*2); 

 $scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>500,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE); 
 $myPicture->drawScale($scaleSettings); 
 
 $myPicture->Antialias = TRUE; 

 $MyData->setSerieDrawable("BTC/USD",TRUE);
 $MyData->setSerieDrawable("EMAshort",TRUE); 
 $MyData->setSerieDrawable("EMAlong",TRUE); 
 $MyData->setSerieDrawable("Buy",FALSE); 
 $MyData->setSerieDrawable("Sell",FALSE); 
 $myPicture->drawLineChart(array("DisplayValues"=>FALSE)); 

 $MyData->setSerieDrawable("BTC/USD",FALSE); 
 $MyData->setSerieDrawable("EMAshort",FALSE);
 $MyData->setSerieDrawable("EMAlong",FALSE); 
 $MyData->setSerieDrawable("Buy",TRUE); 
 $MyData->setSerieDrawable("Sell",TRUE); 
 $myPicture->drawPlotChart(array("DisplayValues"=>TRUE,"PlotBorder"=>FALSE,"BorderSize"=>2,"Surrounding"=>-60,"BorderAlpha"=>80)); 

 $MyData->setSerieDrawable("BTC/USD",TRUE); 
 $MyData->setSerieDrawable("EMAshort",TRUE); 
 $MyData->setSerieDrawable("EMAlong",TRUE); 
 $MyData->setSerieDrawable("Buy",TRUE); 
 $MyData->setSerieDrawable("Sell",TRUE); 
 //$myPicture->writeBounds();
 $myPicture->drawLegend(121,10,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL,"FontR"=>0,"FontG"=>0,"FontB"=>0)); 


/*	$last=explode(",",end($F1));
	if ($last[7]<=1) {
		$least=$emaLong-count($F1);
		echo "<center>************<br><b>$least/$emaLong</b> periods left to process graphic chart.</br>************</center>";
	}else{
		echo "<img src='chart.php";echo "'>";
	}
*/
$myPicture->autoOutput("graph.png"); 


?>


