<?php
// *** Number of lines in info, given that empty lines are FL of normal lines. 

$FL = 0.25; 
// $n = substr_count($info, "\n") + 1; 
$info_arr = explode("\n", $info);
$n = 0;
foreach($info_arr as $line)
{
  $line = trim($line); 
  if (empty($line)) $n += $FL; else $n++;   
}

// *** Font size in pt 

// Font size of MT
$ftMT = 9;
// Font size of Registered symbol
$ftRg = 6;
// Font size of City-Province
$ftCP = 9;
// Font size of Name
$ftN  = 10;
// Font size of Title
$ftT  = 9;
// Font size of Info
$ftI  = 8; 
$ftImm = $ftI * 0.352777; 

// **** Below, the unit is millimeter. Bleed of 1.5 mm on all sides are included in width, height, margins, etc.

// The page heigth
$PH = 54; 
// The page width
$PW = 92; 
// The Right Margin. 
$RM  = 6.5; 
// The base and height of MT line
$bMT = 8.8;
// The base and height of City Province line
$bCP = 12.8; 
// The base and height of Name line
$bN  = 18;
// The base and height of Title line
$bT  = 22;

// **** ML is an intermediary parameter used to set $hI and $bI. Roughly, it corresponds to the Maximum number of Lines in info. 

if($n < 8)
{
  // We only care that it is centered
  $ML  = 7.5;    
}
else
{
  // We start $hI after title. 
  $ML  = $n;    
}
// The height of a line in info. Picked so that, when $n = $ML and info is centered, it starts $hI below Title.  
$hI = ($PH - 1.5 - $bT + $ftImm * .71) / ($ML + 1); 
// The base of info when there are $n lines. One can always add empty lines, so that $n = $ML.  
$bI   = $bT + $hI + ($ML - $n) * ($hI/2);

