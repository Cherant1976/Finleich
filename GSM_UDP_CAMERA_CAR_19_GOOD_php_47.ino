//#include <SPI.h>

#include "SoftwareSerial.h"
#define camBufLen 246// >16

SoftwareSerial SIM(2, 3);
SoftwareSerial CAM(9,10);

uint8_t buf[camBufLen+10];
short len=0;
short start_buf=0;

bool flag_vkl=false;
bool flag_otkl=false;

void setup()
{
  pinMode(11, OUTPUT);//PWM RIGHT
  pinMode(12, OUTPUT);//RIGHT
  pinMode(13, OUTPUT);//RIGHT

  pinMode(6, OUTPUT);//PWM RIGHT
  pinMode(7, OUTPUT);//RIGHT
  pinMode(8, OUTPUT);//RIGHT

  
  String strreq="";
  /* Initialise the serial interface */
  CAM.begin(38400);//38400
  delay(1000);
  SIM.begin(57600);//9600
  Serial.begin(57600);//9600
  delay(1000);
  Serial.println(_SS_MAX_RX_BUFF);
  
  /* delay(10000);
  Power();//Автовключение/выключение SIM900
  delay(5000);
  Power();//Автовключение/выключение SIM900   */
  
  SIM.listen();
  strreq="AT+CMGF=1";//Режим текстовых сообщений
  SIM.println(strreq);
  delay(2000);
  ShowserialSIM();
  strreq="AT+CSCS=\"GSM\"";//Текстовые сообщения Латиницей
  SIM.println(strreq);
  delay(2000);
  ShowserialSIM();
/*  while(!flag_vkl)
  {
   flag_vkl=SMS_vkl();
   Serial.println("VNUTRI");//Udal
  }*/

  Vkl_TCP_Soed();

 // CAM.listen();
 // CAMwork();

 // SIM.listen();
  Obrab_TCP_Soed();
  Otkl_TCP_Soed();
}



/* Main program */
void loop()
{
    
}


bool SMS_vkl()
{
 String strreq="";
 char* str;
 String index;
 String nomer;
 String kod;
 
 while(SIM.available()==0)
 {
  ;
 }
 while(SIM.available()!=0)
 {
  *str=(char(SIM.read()));
  Serial.write(*str);
  strreq.concat(*str);
 }
    
  if (strreq.startsWith("\r\n+CMTI: \"SM\","))//ВАЖНО strreq.startsWith("\r\n+CMTI: \"SM\",")
  {
   index=strreq.substring(14,strreq.length()-2);//\r\n+CMTI: "SM",7\r\n
   SIM.print("AT+CMGR=");
   SIM.println(index);
   delay(2000);
   strreq="";
   while(SIM.available()!=0)
   {
    *str=(char(SIM.read()));
    Serial.write(*str);
    strreq.concat(*str);
   }
    nomer=strreq.substring(34,46);//\r\n+CMGR: "REC UNREAD","+79999999999","","17/12/25,18:47:10+12"\r\nNY2018 работало nomer=strreq.substring(34,46);
    kod=strreq.substring(75,81);//\r\n+CMGR: "REC UNREAD","+79999999999","","17/12/25,18:47:10+12"\r\nNY2018 работало kod=strreq.substring(75,81);
    Serial.println("");//Udal
    Serial.println(nomer);//Udal
    Serial.println(kod);//Udal
    delay(3000);//Udal
      
   if(nomer=="+79999999999"&&kod=="NY2018")
      {
       SIM.print("AT+CMGD=");
       SIM.println(index);
       delay(2000);
       ShowserialSIM();
       return true;
      }
    else
      {
       SIM.print("AT+CMGD=");
       SIM.println(index);
       delay(2000);
       ShowserialSIM();
       return false;
      }    
      
  }
  else
  {
   return false;
  }
  
}

int Vkl_TCP_Soed()
{
  String strreq="";

  strreq="AT+IPR?";
  SIM.println(strreq);
  delay(2000);
  ShowserialSIM();
  
  strreq="AT+IPR=57600";//делал 115200 UDP работало по 100мс но с глюками!!!
  SIM.println(strreq);
  delay(2000);
  ShowserialSIM();
  
  strreq="AT+CIPSHUT";
  SIM.println(strreq);
  delay(2000);
  ShowserialSIM();
  
  strreq="AT+CIPMUX=0";// "AT+CIPMUX=1" multisocket
  SIM.println(strreq);
  delay(2000);
  ShowserialSIM();
  
  strreq="AT+CGATT=1";
  SIM.println(strreq);
  delay(2000);
  ShowserialSIM();
  
  strreq="AT+CSTT=\"internet.beeline.ru\",\"beeline\",\"beeline\"";
  SIM.println(strreq);
  delay(2000);
  ShowserialSIM();
  
  strreq="AT+CIICR";
  SIM.println(strreq);
  delay(3000);
  ShowserialSIM();
  
  strreq="AT+CIFSR";
  SIM.println(strreq);
  delay(3000);
  ShowserialSIM();
  
  strreq="AT+CIPQSEND=1";
  SIM.println(strreq);
  delay(3000);
  ShowserialSIM();
  
  strreq="AT+CIPSPRT=2";
  SIM.println(strreq);
  delay(3000);
  ShowserialSIM();
  
  strreq="AT+CIPSTART=\"UDP\",\"999.99.99.999\",\"5555\"";//"AT+CIPSTART=0,\"UDP\",\"999.99.99.999\",\"5555\"" multisocket (first 0)
  SIM.println(strreq);
  delay(5000);
  ShowserialSIM();
  
 /* strreq="AT+CIPSEND=40";//strreq="AT+CIPSEND";"AT+CIPSEND=0,40" multisocket
  SIM.println(strreq);
  delay(100);
  ShowserialSIM();
  SIM.print("123456789ABCDEFGHPOT123456789ABCDEFGHP70");//SIM.println("SOOBSHENIE UDP!");
  delay(100);
  ShowserialSIM();
  delay(700);//700 Работало при длине сообщения 18, 5000 для тестов
  ShowserialSIM();
  delay(2000);*/
}


void Otkl_TCP_Soed()
{
  String strreq="";
  strreq="AT+CIPSHUT";
  SIM.println(strreq);
  delay(2000);
  ShowserialSIM();  

}


void Obrab_TCP_Soed()
{
  String strreq="";
  int z=9960;
  unsigned long time=millis();
 
  while(!flag_otkl)//30256
  {
   if((millis()-time)>10)//работало >10ms (Ar 10ms Intern 0.1ms)
    {
      strreq="AT+CIPSEND=2";//"AT+CIPSEND=0,40" multisocket
      SIM.println(strreq);
      delay(30);//30 Работало при длине сообщения 1000
      ShowserialSIM();
      SIM.write(z/256);
      SIM.write(z%256);
      delay(10);//10 Работало при длине сообщения 1000
      ShowserialSIM();
      delay(10);//10 Работало при длине сообщения 1000, 5000 милисек для тестов
      //SIM.read();//ShowserialSIM();
      z++;
      time=millis();
    }  
      delay(10);
      ShowPriem();
   
  }

}


void ShowPriem()
{
 unsigned int control1=0;
 unsigned int control2=2;
 unsigned int up=0;
 unsigned int down=0;
 unsigned int right=0;
 unsigned int left=0;

 float KR=1;
 float KL=1;
 
 while(SIM.available()!=0)
 {
    control1=SIM.read();
      up=SIM.read();
      down=SIM.read();
    control2=SIM.read();
       right=SIM.read();
      left=SIM.read();
    if(control1==1&&control2==2)
    {  
      KR=(1-(right/250));
      KL=(1-(left/250));
      if (up>0)
      {
        analogWrite(6,up*KL);//PWM RIGHT
        digitalWrite(7,HIGH);//HIGH FORWARD RIGHT, LOW  REAR RIGHT
        digitalWrite(8,LOW);// LOW FORWARD RIGHT, HIGH  REAR RIGHT
        analogWrite(11,up*KR);//PWM LEFT
        digitalWrite(12,HIGH);//HIGH FORWARD LEFT, LOW  REAR LEFT
        digitalWrite(13,LOW);// LOW FORWARD LEFT, HIGH  REAR LEFT
      }
      if (down>0)
      {
        analogWrite(6,down*KL);//PWM RIGHT
        digitalWrite(7,LOW);//HIGH FORWARD RIGHT, LOW  REAR RIGHT
        digitalWrite(8,HIGH);// LOW FORWARD RIGHT, HIGH  REAR RIGHT
        analogWrite(11,down*KR);//PWM LEFT
        digitalWrite(12,LOW);//HIGH FORWARD LEFT, LOW  REAR LEFT
        digitalWrite(13,HIGH);// LOW FORWARD LEFT, HIGH  REAR LEFT
      }
      if(up==0&&down==0)
      {
        digitalWrite(7,LOW);//HIGH FORWARD RIGHT, LOW  REAR RIGHT
        digitalWrite(8,LOW);// LOW FORWARD RIGHT, HIGH  REAR RIGHT
        analogWrite(11,0);//PWM LEFT
      }
    }
    else if(control1==120&&control2==230)
    {
      digitalWrite(7,LOW);//HIGH FORWARD RIGHT, LOW  REAR RIGHT
      digitalWrite(8,LOW);// LOW FORWARD RIGHT, HIGH  REAR RIGHT
      analogWrite(11,0);//PWM LEFT
      CAM.listen();
      CAMwork();
      SIM.listen();
      control1=1;
      control2=2;
    }    
    else if(control1==129&&control2==235)
    {
      digitalWrite(7,LOW);//HIGH FORWARD RIGHT, LOW  REAR RIGHT
      digitalWrite(8,LOW);// LOW FORWARD RIGHT, HIGH  REAR RIGHT
      analogWrite(11,0);//PWM LEFT
      flag_otkl=true;
    }
  }
}

void ShowserialSIM()
{
while(SIM.available()!=0)
    SIM.read();
}

void Power()
{
 pinMode(9, OUTPUT); 
 digitalWrite(9,LOW);
 delay(2000);
 digitalWrite(9,HIGH);
 delay(2000);
 digitalWrite(9,LOW);
 delay(3000);
}

void CAMwork()
{
  uint8_t datcam;
  String strreq;
  short contrl=107;
  
  CAM.listen();
  
  FreezCAM();

  delay(50);
  ShowserialCAM();

  DlinaKadra();

  delay(50); 
  ShowserialCAM();

  SIM.listen();
  strreq="AT+CIPSEND=7";//"AT+CIPSEND=0,40" multisocket
  SIM.println(strreq);
  delay(30);//30 Работало при длине сообщения 1000
  SIM.print(contrl);
  SIM.print(len);
  ShowserialSIM();
  delay(30);//30 Работало при длине сообщения 1000
  ShowserialSIM();
  delay(10);//10 Работало при длине сообщения 1000
  ShowserialSIM();
  delay(10);
  
  CAM.listen(); 

  AntiFreezCAM();

  delay(300);//300
  ShowserialCAM();
  
  while(start_buf<(len-camBufLen+1))
  {
    datcam=0x56;
    CAM.write(datcam);
    
    datcam=0x00;
    CAM.write(datcam);
 
    datcam=0x32;
    CAM.write(datcam);
    
    datcam=0x0C;
    CAM.write(datcam);
  
    datcam=0x00;
    CAM.write(datcam);
  
    datcam=0x0F;//0x0F
    CAM.write(datcam);
  
    datcam=0x00;//Start 1
    CAM.write(datcam);
  
    datcam=0x00;//Start 2
    CAM.write(datcam);
  
    datcam=start_buf/256;//Start 3
    CAM.write(datcam);
//    Serial.print(" S3-0x");
//    Serial.println(datcam,HEX);
  
    datcam=start_buf%256;//Start 4
    CAM.write(datcam);
//    Serial.print(" S4-0x");
//    Serial.println(datcam,HEX);
  
    datcam=0x00;//Lenght 1
    CAM.write(datcam);
  
    datcam=0x00;//Lenght 2
    CAM.write(datcam);
  
    datcam=camBufLen/256;//Lenght 3
    CAM.write(datcam);
//    Serial.print(" L3-0x");
//    Serial.println(datcam,HEX);
  
    datcam=camBufLen%256;//Lenght 4
    CAM.write(datcam);
//    Serial.print(" L4-0x");
//    Serial.println(datcam,HEX);
      
    datcam=0x00;//Delay 1
    CAM.write(datcam);
  
    datcam=0x00;//Delay 2
    CAM.write(datcam);
    
    delay(50);
    ShowserialCAM();
    ShowBuf(camBufLen);

    start_buf=start_buf+camBufLen;
  }

    datcam=0x56;
    CAM.write(datcam);
    
    datcam=0x00;
    CAM.write(datcam);
 
    datcam=0x32;
    CAM.write(datcam);
    
    datcam=0x0C;
    CAM.write(datcam);
  
    datcam=0x00;
    CAM.write(datcam);
  
    datcam=0x0F;//0x0F
    CAM.write(datcam);
  
    datcam=0x00;//Start 1
    CAM.write(datcam);
  
    datcam=0x00;//Start 2
    CAM.write(datcam);
  
    datcam=start_buf/256;//Start 3
    CAM.write(datcam);
//    Serial.print(" S3-0x");
//    Serial.println(datcam,HEX);
  
    datcam=start_buf%256;//Start 4
    CAM.write(datcam);
//    Serial.print(" S4-0x");
//   Serial.println(datcam,HEX);
  
    datcam=0x00;//Lenght 1
    CAM.write(datcam);
  
    datcam=0x00;//Lenght 2
    CAM.write(datcam);
  
    datcam=(len-start_buf)/256;//Lenght 3
    CAM.write(datcam);
//    Serial.print(" L3-0x");
//    Serial.println(datcam,HEX);
  
    datcam=(len-start_buf)%256;//Lenght 4
    CAM.write(datcam);
//    Serial.print(" L4-0x");
//    Serial.println(datcam,HEX);
  
    datcam=0x00;//Delay 1
    CAM.write(datcam);
  
    datcam=0x00;//Delay 2
    CAM.write(datcam);
    
    delay(50);//100
    ShowserialCAM();
    ShowBuf(len-start_buf);
    
    start_buf=0;

    //CAM.flush();
    //ShowserialCAM();

}

void FreezCAM()
{
  uint8_t datcam;
  
  datcam=0x56;
  CAM.write(datcam);
  
  datcam=0x00;
  CAM.write(datcam);
  
  datcam=0x36;
  CAM.write(datcam);
  
  datcam=0x01;
  CAM.write(datcam);

  datcam=0x00;
  CAM.write(datcam);
}

void DlinaKadra()
{
  uint8_t datcam;
  
  datcam=0x56;
  CAM.write(datcam);
  
  datcam=0x00;
  CAM.write(datcam);
  
  datcam=0x34;
  CAM.write(datcam);
  
  datcam=0x01;
  CAM.write(datcam);

  datcam=0x00;
  CAM.write(datcam);
}

void AntiFreezCAM()
{
  uint8_t datcam;
  
  datcam=0x56;
  CAM.write(datcam);
    
  datcam=0x00;
  CAM.write(datcam);
    
  datcam=0x36;
  CAM.write(datcam);
    
  datcam=0x01;
  CAM.write(datcam);
  
  datcam=0x02;
  CAM.write(datcam);
}

void ShowserialCAM()
{
 short i=0;
 boolean flag=false;
 
    while(CAM.available())
    {
    buf[i]=CAM.read();
    Serial.print("1");
    i++;
    }
 
   if(buf[0]==118&&buf[2]==52)
   { 
    len=buf[7]*16*16+buf[8];
    Serial.print(" Lenght - ");
    Serial.print(len,DEC);
   }
}

void ShowBuf(int razm)
{
 String strreq="";
 short i=5;
  
  SIM.listen();
  Serial.println(razm,DEC); 
  
  strreq="AT+CIPSEND=";//"AT+CIPSEND=0,40" multisocket
  strreq.concat(razm);
  SIM.println(strreq);
  delay(30);//30 Работало при длине сообщения 1000
  Serial.println(strreq);
  ShowserialSIM();

  SIM.write(buf+5,razm);
  Serial.write(buf+5,razm);
/* for(i=5;i<(razm+5);i++)
    {
      SIM.write(buf[i]);
    }*/
       
  delay(40);//40 работает, но хорошо работало при 100 
  //SIM.flush();
  ShowserialSIM();
  delay(10);//10
  //SIM.flush();
  ShowserialSIM();
  delay(10);//10
  
  CAM.listen();
}
