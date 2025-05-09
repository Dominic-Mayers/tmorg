<?php
//IMPORTANT FPDF does not accept UTF-8, so keep this file encoded in cp.

require("fpdf/fpdf.php"); 
$lang = $_POST['lang'];
$MTTM = $lang == 'en' ? "TRANSCENDENTAL MEDITATION": "MÉDITATION TRANSCENDANTALE";

$name = isset($_POST['name'])? $_POST['name']: 'FirstName LastName';
$name_for_file = str_replace(" ","_",stripAccents($name));

$degree = isset($_POST['degree'])? $_POST['degree']: '';
$font_size =  isset($_POST['font_size'])? $_POST['font_size']: '7';
$title = isset($_POST['title'])? $_POST['title']: 'Certified Teacher'; 
$city_prov = isset($_POST['city_prov'])? $_POST['city_prov']: 'City, Province'; 
$info = isset($_POST['info'])? $_POST['info'] : "Ste 108, 108 avenue City Centre\nOttawa, ON, K1R 6K7\n(b) 613 108-1080\n(d) 613 108-0108\ngbliss@tm.org\nwww.tm.org";
 
$name = mb_check_encoding($name,"UTF-8") ? mb_convert_encoding($name,"ISO-8859-1", "UTF-8"): $name; 
$title = mb_check_encoding($title,"UTF-8") ? mb_convert_encoding($title,"ISO-8859-1", "UTF-8"): $title; 
$city_prov = mb_check_encoding($city_prov,"UTF-8") ? mb_convert_encoding($city_prov,"ISO-8859-1", "UTF-8"): $city_prov; 
$info = mb_check_encoding($info,"UTF-8") ? mb_convert_encoding($info,"ISO-8859-1", "UTF-8"): $info; 

class PDF_EN extends FPDF
{
  // Page header
  function Header()
  {
    $this->Image("images_card/Business_Card_TM_Pale_logo_no_info.jpg",0,0,91,54);
  }
}
class PDF_FR extends FPDF
{
  // Page header
  function Header()
  {
    $this->Image("images_card/Carte_Affaire_MT_Pale_logo_no_info.jpg",0,0,91,54);
  }
}

include ("measures.php"); 


$pdf = $lang=='en'? new PDF_EN() : new PDF_FR();
$pdf->SetAutoPageBreak(false); 
$pdf->AddPage('L',array($PH,$PW));
$pw = $pdf->GetPageWidth(); 
$ph = $pdf->GetPageHeight(); 

$pdf->AddFont('Trebuchet','','Trebuchet_MS.php');


$pdf->SetTextColor(232, 167, 19);
$pdf->SetFont('Trebuchet','',$ftRg);
$w  = $pdf->GetStringWidth('®');
// Font scale 1pt = .352777mm
$ftMTmm = $ftMT * 0.352777; 
$ftRgmm = $ftRg * 0.352777; 
$pdf->Text($pw - $RM, $bMT - $ftMTmm + $ftRgmm, '®'); // - 1.5 mm because superscript. 
$pdf->SetFont('Trebuchet','',$ftMT); 
$w  = $pdf->GetStringWidth($MTTM);
$pdf->Text($pw - $RM - $w, $bMT ,$MTTM); 

$w  = $pdf->GetStringWidth($city_prov);
$pdf->Text($pw - $RM - $w, $bCP , $city_prov); 

$pdf->SetTextColor(0, 102, 164);

$pdf->SetFont('Trebuchet','',$font_size);
$w  = $pdf->GetStringWidth($degree); 
$pdf->Text($pw - $RM - $w, $bN , $degree); 

$pdf->SetFont('Trebuchet','',$ftN);
$w = $pdf->GetStringWidth($name) + $w;
$pdf->Text($pw - $RM - $w, $bN , $name); 

$pdf->SetFont('Trebuchet','',$ftT);
$w = $pdf->GetStringWidth($title);
$pdf->Text($pw - $RM - $w, $bT , $title); 

$pdf->SetFont('Trebuchet','',$ftI);
$info_arr = explode("\n",$info); 
$bI_current =  $bI; 
foreach($info_arr as $k => $inf_line)
{ 
  $inf_line = trim($inf_line); 
  $w = $pdf->GetStringWidth($inf_line);
  if(empty($inf_line)) 
  {
    $bI_current += $FL * $hI;
    continue; 
  }
  $pdf->Text($pw - $RM - $w, $bI_current, $inf_line); 
  $bI_current += $hI;
}

$pdf_content = $pdf->Output("business_card_$name_for_file",'S');
//header("Content-type:application/pdf");
echo $pdf_content; 


function stripAccents($str)
{ 
  $from = mb_convert_encoding('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ', "UTF-8","ISO-8859-1"); // Only convert if the file is in ISO-8859-1.
  // $from = 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'; 

  $to = 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY'; 
	$str = strtr_utf8($str,$from,$to);
  return $str; 
}

function strtr_utf8($str, $from, $to) 
{
    $keys = array();
    $values = array();
    preg_match_all('/./u', $from, $keys);
    preg_match_all('/./u', $to, $values);
    $mapping = array_combine($keys[0], $values[0]);
    return strtr($str, $mapping);
}
?>
