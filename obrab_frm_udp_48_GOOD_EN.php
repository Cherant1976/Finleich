<?php 
require("Sajax.php"); 
$basa=@new mysqli('to.mysql','to_mysql','99-9999','to_db');
$photo=FALSE;

function FuncSocOpen() {
	
	global $basa;
	global $photo;
	
	$address = "999.99.99.999";
	$port = 5555;
    
	$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
	   
   if( !$socket ) exit( socket_strerror( socket_last_error() ) );
   else echo 'Socket_created!'."\r\n";
   
   socket_bind($socket, $address, $port);
   socket_listen($socket, 10);//  socket_listen($socket, 10);
  
   $clientip = '';
   $clientport = 0;
  
   socket_recvfrom($socket,$result,1024,0,$clientip,$clientport);
   
  //$result = socket_read($socket,1024);   //Это работало с UDP
   $result="POLUCHENNAYA DATA-".$result;
   socket_sendto($socket,$result,strlen($result),0,$clientip,$clientport);
 // socket_write($socket,"POLUCHENNAYA DATA-".$result."\r\n");//?????
 // socket_set_nonblock($socket);   
 // $basa=@new mysqli('to.mysql','to_mysql','99-9999','to_db');
  
  $vrem=microtime(true);
  $flag=TRUE;
	while($flag) //было 30256, 256, 90256
	{
		
			if((microtime(true)-$vrem)>0.0001)//Работало при 0.0001 ms
					{	  
					//socket_set_block($socket);
					socket_recvfrom($socket,$resultsoc,2,0,$clientip,$clientport);//Было socket_recvfrom($socket,$result,1024,0,$clientip,$clientport);
					$resultsoc=str_replace("'","''",$resultsoc);//Иначе SQL неправильно виспринимал символ одинароной кавычки '
					$q_str1="UPDATE UP_DOWN SET MONITOR="."'".$resultsoc."'";
					$q_str=$q_str1." WHERE S_ID=1";
					$rezultb=@$basa->query($q_str);
					//socket_set_nonblock($socket);
					$vrem=microtime(true);
					}
				
				if((ord($mas['UDRL'][0])==120)&&(ord($mas['UDRL'][3])==230))
				{
					$photo=TRUE;
					FuncPHOTO($socket);
					FuncUDRL(0,0,0,0);
					$photo=FALSE;
				}
								
				
				$q_str="SELECT UDRL FROM UP_DOWN WHERE S_ID=(SELECT max(S_ID) FROM UP_DOWN)";
				$rezultm=@$basa->query($q_str);
				$mas=@$rezultm->fetch_assoc();
				$result=$mas['UDRL'];
				socket_sendto($socket,$result,strlen($result),0,$clientip,$clientport);//вместо strlen($result) можно сделатьпросто равно 6
				
				
								
				if((ord($mas['UDRL'][0])==129)&&(ord($mas['UDRL'][3])==235))
				{
					$flag=FALSE;
				}
				
		
	} 
	socket_close($socket);
		
	$basa->close();
}

function FuncSpec()
{
 global $basa;
 global $photo;
	if($photo==FALSE)
	{
		 $q_str="SELECT MONITOR FROM UP_DOWN WHERE S_ID=(SELECT max(S_ID) FROM UP_DOWN)";
		 $rezult=@$basa->query($q_str);
		 $mas=@$rezult->fetch_assoc();
		 $pobyt1=256*ord($mas['MONITOR'][0]);//$pobyt1=256*ord($mas['MONITOR'][0]);
		 $pobyt2=ord($mas['MONITOR'][1]);//$pobyt2=ord($mas['MONITOR'][1]);
		 $monitor=$pobyt1+$pobyt2;//НО для ord() при работе с SQL для поля MONITOR надо выбрать режим сравнения utf8_bin, $monitor=$mas['MONITOR'];ord($mas['MONITOR'])
		 return $monitor;
	}
}	

function FuncUDRL($UP,$DOWN,$RIGHT,$LEFT,$CNTRL1=1,$CNTRL2=2)	
{	
  	global $basa;
	
	$control1=chr($CNTRL1);//$control1=chr(1);
	$otbyt1=chr($UP);
	$otbyt2=chr($DOWN);
	$control2=chr($CNTRL2);//$control2=chr(2);
	$otbyt3=chr($RIGHT);
	$otbyt4=chr($LEFT);
	//$control=str_replace("'","''",$control);//Иначе SQL неправильно воспринимал символ одинароной кавычки '
	$otbyt1=str_replace("'","''",$otbyt1);//Иначе SQL неправильно воспринимал символ одинароной кавычки '
	$otbyt2=str_replace("'","''",$otbyt2);//Иначе SQL неправильно воспринимал символ одинароной кавычки '
	$otbyt3=str_replace("'","''",$otbyt3);//Иначе SQL неправильно воспринимал символ одинароной кавычки '
	$otbyt4=str_replace("'","''",$otbyt4);//Иначе SQL неправильно воспринимал символ одинароной кавычки '
	
	$q_str1="UPDATE UP_DOWN SET UDRL="."'".$control1.$otbyt1.$otbyt2.$control2.$otbyt3.$otbyt4."'"; 
	$q_str=$q_str1." WHERE S_ID=1";//S_ID=1!!! пока так
	$rezult=@$basa->query($q_str);

}	

function FuncPHOTO($sock) 
{
//global $socket;// = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);



//socket_bind($socket, $address, $port);
//	socket_listen($socket, 10);//  socket_listen($socket, 10);	
	global $basa;
	global $photo;
	
	$photo=TRUE;
		
	$fp = fopen("image.jpg", "w");

	$clientip = '';
	$clientport = 0;
	
	$razm=246;
	$contrl=0;
	
		
	$k=0;
	while(($contrl<>107)&&($k<50))//30 k<50
	{
	$q_str="SELECT UDRL FROM UP_DOWN WHERE S_ID=(SELECT max(S_ID) FROM UP_DOWN)";
	$rezultm=@$basa->query($q_str);
	$mas=@$rezultm->fetch_assoc();
	$result=$mas['UDRL'];
	socket_sendto($sock,$result,strlen($result),0,$clientip,$clientport);//вместо strlen($result) можно сделатьпросто равно 6
	
	socket_recvfrom($sock,$result,7,0,$clientip,$clientport);
	$contrl=substr($result,0,3);
	$k_fin=substr($result,3,4);
	
	$k++;
	}
	
		
	if($contrl==107)
	{	
		$k=0;
		while($k<(intdiv($k_fin,$razm)))
		{	
			socket_recvfrom($sock,$result,$razm,0,$clientip,$clientport);
			fwrite($fp,$result);
			$k++;
		}
		socket_recvfrom($sock,$result,$k_fin%$razm,0,$clientip,$clientport);
		fwrite($fp,$result);
	
	
	
	
	} 
 // socket_close($socket);
  fclose($fp);
    
  $photo=FALSE;  
}


sajax_init(); 
// Раскомментировать для отладочного режима 
// $sajax_debug_mode = 1; 
sajax_export("FuncSocOpen");
sajax_export("FuncSpec");
sajax_export("FuncUDRL");
//sajax_export("FuncPHOTO");

sajax_handle_client_request(); 
?>

<html>
<head>
<title>CHERANT</title>
 </head>
 <body>

<script> 
<?php sajax_show_javascript(); ?> 
	var up=0;
	var down=0;
	var right=0;
	var left=0;
	var mouse_down_up=false;
	var mouse_down_down=false;
	var mouse_right_down=false;
	var mouse_left_down=false;
	
	setInterval(FuncSpec,1000);//50 3000, 100
	setInterval(FuncReload,2000);//1000
		
	function do_FuncSocOpen_cback(result) {

	}
	function FuncSpec_cback(result) { 
	  document.getElementById("Monitor").value = result;
	}

	function FuncUDRL_cback(result) { 
	  
	}

	function FuncSocOpen() {
	  x_FuncSocOpen();//x_FuncSocOpen(do_FuncSocOpen_cback);
	}
	
	function FuncCloseSoed() {
	 x_FuncUDRL(0,0,0,0,129,235,FuncUDRL_cback);//x_FuncUDRL(up,down,right,left,FuncUDRL_cback);x_FuncUDRL(0,0,0,0,129,237,FuncUDRL_cback);
	 // x_FuncSocOpen();//x_FuncSocOpen(do_FuncSocOpen_cback);
	}
	
	function FuncSpec() {
	 x_FuncSpec(FuncSpec_cback);
	}
	
	function FuncReload() {
	 document.getElementById("Photo").src='image.jpg?'+ Math.random();//'image.php?' + Math.random();
	}
	
	function FuncUP() {
	//down=0;
	document.getElementById("Monitor_DOWN").value=down;
	mouse_down_up = true;
    callEvent_FuncUP();
	}
	
	function callEvent_FuncUP() {
		 if(mouse_down_up)
		 {
			if (down==0)
			{
				if(up==0)
				{
				up=55;
				}
				else
				{
				up=up+5;
				}
					
				if (up==125)
				{
				up=120;
				}
			}
			else
			{
				if(down==55)
				{
				down=0;
				}
				else
				{
				down=down-5;
				}
			}
			document.getElementById("Monitor_UP").value=up;
			document.getElementById("Monitor_DOWN").value=down;
			// do whatever you want
			// it will continue executing until mouse is not released
			x_FuncUDRL(up,down,right,left,FuncUDRL_cback);
			setTimeout("callEvent_FuncUP()",30);//1
		 }
		 else
		 return;
	}
	
	function FuncUPmouseup() {
	//up=0;
	mouse_down_up = false;
	document.getElementById("Monitor_UP").value=up;
	//x_FuncUDRL(up,down,right,left,FuncUDRL_cback);
	}	
	
	function FuncDOWN() {
	document.getElementById("Monitor_UP").value=up;
	mouse_down_down = true;
    callEvent_FuncDOWN();
	}
	
	function callEvent_FuncDOWN() {
		 if(mouse_down_down)
		 {
		   	if (up==0)
			{
				if(down==0)
				{
				down=55;
				}
				else
				{
				down=down+5;
				}
				
				if (down==125)
				{
				down=120;
				}
			}
			else
			{
				if(up==55)
				{
				up=0;
				}
				else
				{
				up=up-5;
				}
			}
			document.getElementById("Monitor_DOWN").value=down;
			document.getElementById("Monitor_UP").value=up;
			// do whatever you want
			// it will continue executing until mouse is not released
			x_FuncUDRL(up,down,right,left,FuncUDRL_cback);
			setTimeout("callEvent_FuncDOWN()",30);//1
		 }
		 else
		 return;
	}
	
	function FuncDOWNmouseup() {
	mouse_down_down = false;
	document.getElementById("Monitor_DOWN").value=down;
	//x_FuncUDRL(up,down,right,left,FuncUDRL_cback);
	}
	
	function FuncRIGHT() {
	document.getElementById("Monitor_RIGHT").value=right;
	mouse_right_down = true;
    callEvent_FuncRIGHT();
	}
	
	function callEvent_FuncRIGHT() {
		 if(mouse_right_down)
		 {
		   	if (left==0)
			{
			right=right+50;
				if (right==300)
				{
				right=250;
				}
			}
			else
			{
			left=left-50;
			}
			document.getElementById("Monitor_RIGHT").value=right;
			document.getElementById("Monitor_LEFT").value=left;
			// do whatever you want
			// it will continue executing until mouse is not released
			x_FuncUDRL(up,down,right,left,FuncUDRL_cback);
			setTimeout("callEvent_FuncRIGHT()",30);//1
		 }
		 else
		 return;
	}
	
	function FuncRIGHTmouseup() {
	mouse_right_down = false;
	document.getElementById("Monitor_RIGHT").value=right;
	//x_FuncUDRL(up,down,right,left,FuncUDRL_cback);
	}
		
	function FuncLEFT() {
	document.getElementById("Monitor_LEFT").value=left;
	mouse_left_down = true;
    callEvent_FuncLEFT();
	}
	
	function callEvent_FuncLEFT() {
		 if(mouse_left_down)
		 {
		   	if (right==0)
			{
			left=left+50;
				if (left==300)
				{
				left=250;
				}
			}
			else
			{
			right=right-50;
			}
			document.getElementById("Monitor_LEFT").value=left;
			document.getElementById("Monitor_RIGHT").value=right;
			// do whatever you want
			// it will continue executing until mouse is not released
			x_FuncUDRL(up,down,right,left,FuncUDRL_cback);
			setTimeout("callEvent_FuncLEFT()",30);//1
		 }
		 else
		 return;
	}
	
	function FuncLEFTmouseup() {
	mouse_left_down = false;
	document.getElementById("Monitor_LEFT").value=left;
	//x_FuncUDRL(up,down,right,left,FuncUDRL_cback);
	}
	
	function FuncSTOP() {
	up=0;
	down=0;
	right=0;
	left=0;
	document.getElementById("Monitor_UP").value=up;
	document.getElementById("Monitor_DOWN").value=down;
	document.getElementById("Monitor_RIGHT").value=right;
	document.getElementById("Monitor_LEFT").value=left;
	x_FuncUDRL(up,down,right,left,FuncUDRL_cback);
	}
	
	function FuncPHOTO() {
	up=0;
	down=0;
	right=0;
	left=0;
	document.getElementById("Monitor_UP").value=up;
	document.getElementById("Monitor_DOWN").value=down;
	document.getElementById("Monitor_RIGHT").value=right;
	document.getElementById("Monitor_LEFT").value=left;
	var i;
	for (i = 0; i < 10; i++) {
	  x_FuncUDRL(up,down,right,left,120,230,FuncUDRL_cback);
	}
	x_FuncUDRL(up,down,right,left,120,230,FuncUDRL_cback);
//	x_FuncPHOTO();
	
	}	
			
</script>

<p align='center'>
INTERNET CONTROL ARDUINO CAR (SIM 900).
</p>

<form> <!--WITHOUT form NOT working -->
	<table align='left' width='50%' border='0'>
		<tr>
			<td align='center'>
    			<input type='button' value='    OK    ' size='50' onclick="FuncSocOpen()"/>
			</td>
		</tr>
		<tr>
			<td align='center'>
    			<input type='button' value='DISCONNECT' size='50' onclick="FuncCloseSoed()"/>
			</td>
		</tr>
		<tr>
			<td width='150'>DATA FROM CAR:</td>
			<td><output type='text' id='Monitor' size='30'/></td>
		</tr>
		<tr>
			<td width='50'>RIGHT:</td>
                        <td><output type='text' id='Monitor_RIGHT' size='40'/></td>
		</tr>
		<tr>
			<td width='50'>LEFT:</td>
		        <td><output type='text' id='Monitor_LEFT' size='40' align='right'/></td>
                </tr>
		<tr>
			<td align='left'>
    			<IMG SRC='Images/LEFT.jpg' HEIGHT=260 WIDTH=266 ALIGN=MIDDLE onmousedown='FuncLEFT();this.src="Images/LEFT_2.jpg"' onmouseup='FuncLEFTmouseup();this.src="Images/LEFT.jpg"'>
			</td>
			<td align='right'>
				<IMG SRC='Images/RIGHT.jpg' HEIGHT=260 WIDTH=266 ALIGN=MIDDLE onmousedown='FuncRIGHT();this.src="Images/RIGHT_2.jpg"' onmouseup='FuncRIGHTmouseup();this.src="Images/RIGHT.jpg"'>
			</td>
		</tr>
		<tr>
			<td align='center'>
    			<IMG SRC="Images/STOP.jpg" HEIGHT=260 WIDTH=266 ALIGN=MIDDLE onclick="FuncSTOP()">
			</td>
			<td align='right'>
    			<IMG SRC="image.jpg" id='Photo' HEIGHT=240 WIDTH=320 ALIGN=MIDDLE onclick="FuncPHOTO()">
			</td>
		</tr>
	</table>
	<table align='right' width='50%' border='0'>
		<tr>
			<td align='left'>UP:   <output type='text' id='Monitor_UP' size='40'/></td>
		</tr>
		<tr>
			<td align='center'>
    			<IMG SRC='Images/UP.jpg' HEIGHT=260 WIDTH=266 ALIGN=MIDDLE onmousedown='FuncUP();this.src="Images/UP_2.jpg"' onmouseup='FuncUPmouseup();this.src="Images/UP.jpg"'>
			</td>
		</tr>
		<tr>
			<td align='center'>
    			<IMG SRC='Images/DOWN.jpg' HEIGHT=260 WIDTH=266 ALIGN=MIDDLE onmousedown='FuncDOWN();this.src="Images/DOWN_2.jpg"' onmouseup='FuncDOWNmouseup();this.src="Images/DOWN.jpg"'>
			</td>
		</tr>
		<tr>
			<td align='left'>DOWN:   <output type='text' id='Monitor_DOWN' size='40'/></td>
		</tr>		
	</table>
 </form>
 </body>
 </html>

