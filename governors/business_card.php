<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <link rel="manifest" href="/governors/manifest.json">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width">
<link rel="apple-touch-icon" href="images/tm-192.png">
<meta name="theme-color" content="white"/>
<title>TM | Business Card</title>
<link href="/css_card/general.css" rel="stylesheet" type="text/css" />
<link href="/css_card/template.css" rel="stylesheet" type="text/css" />
<link href="/css_card/white.css" rel="stylesheet" type="text/css" />
<link href="/css_card/white_bg.css" rel="stylesheet" type="text/css" />
<?php
if ( isset($_POST['lang']) ) {
  // The default for garbage is 'en', but this script will not post garbage to itself. 
  $lang = $_POST['lang'] == 'fr' ? 'fr': 'en';
} else { 
  $lang =  isset($_GET['fr']) ? 'fr': 'en';
}
$font_size_unsafe = isset($_POST['font_size'])? $_POST['font_size'] : "7.5"; // For degree
$font_size = preg_match("/[^0-9.]/", $font_size_unsafe) ? "7.5" : $font_size_unsafe;
if ($lang=='en')
{
  $city_prov_unsafe = isset($_POST['city_prov']) ? $_POST['city_prov'] : "Toronto, Ontario";
  $name_unsafe = isset($_POST['name'])? $_POST['name'] : "Sarah Hea";
  $degree_unsafe = isset($_POST['degree'])? $_POST['degree'] : ", Ph.D.";
  $title_unsafe = isset($_POST['title'])? $_POST['title'] : "Certified Teacher";
  $info_unsafe = isset($_POST['info'])? $_POST['info'] : "Suite 1808, 438 University Ave\r\nToronto, ON, M5G 2R5\r\n416-964-1725\r\nshea@tm.org";
  $filename_prefix = "business_card_";
  $MTTM = "TRANSCENDENTAL MEDITATION";
  $logo_img = $lang=='en' ? "Business_Card_TM_Pale_logo_no_text.jpg": "Carte_Affaire_MT_Pale_logo_no_text.jpg";
  $logo_banner = "logo";
  $subject_prefix = "Business Card";
  $admin_email = $_ENV['CARD_ADMIN_EMAIL_EN'];
}
else
{
  $city_prov_unsafe = isset($_POST['city_prov']) ? $_POST['city_prov'] : "Sherbrooke, Québec";
  $name_unsafe = isset($_POST['name'])? $_POST['name'] : "Dominic Mayers";
  $degree_unsafe = isset($_POST['degree'])? $_POST['degree'] : ", Ph.D.";
  $title_unsafe = isset($_POST['title'])? $_POST['title'] : "Professeur Certifié";
  $info_unsafe = isset($_POST['info'])? $_POST['info'] : "831 rue McGregor\r\nSherbrooke, QC, J1L 3B4\r\n514-664-3670\r\n819-340-0914\r\ndmayers@tm.org\r\nwww.tm.org";
  $filename_prefix = "carte_affaire_";
  $MTTM = "MÉDITATION TRANSCENDANTALE";
  $logo_img = "Carte_Affaire_MT_Pale_logo_no_text.jpg";
  $logo_banner = "logo_fr";
  $subject_prefix = "Carte affaire";
  $admin_email = $_ENV['CARD_ADMIN_EMAIL_FR'];
}

//**************************************************************
// Computing the email where to send the pdf
//**************************************************************
$note = ""; 
if ( isset($_POST['update']) && ! empty($_POST['email']) ) {
  // We keep the email, because it is only an update. 
  $email = $_POST['email'];
} elseif (! empty($_POST['email']) && $_POST['email'] !== $admin_email ) {
  // We don't have to check the admin email
  $email = $_POST['email']; 
  $email_arr = explode("@", $email);
  $email_name = $email_arr[0]; 
  $email_domain = isset($email_arr[1]) ? $email_arr[1] : "empty";   
  if ( $email_domain !== "tm.org" ) { 
     $note = "The email must be in the tm.org domain.";
     $email = $admin_email;
  } elseif (! filter_var("$email_name@tm.org", FILTER_VALIDATE_EMAIL) ) {
     $note = "The email must be valid.";
     $email = $admin_email;
  } else {
     $email = "$email_name@tm.org";  
  }
} else {
  // We use the admin email, if it is what it is or it is empty. 
  $email = $admin_email; 
}
//**************************************************************

$req=strtok($_SERVER["REQUEST_URI"],'?');

$city_prov = sanitize_text($city_prov_unsafe);
$name =  sanitize_text($name_unsafe);
$degree =  sanitize_text($degree_unsafe);
$title = sanitize_text($title_unsafe);
$info = sanitize_text($info_unsafe);
$filename = $filename_prefix . $name . ".pdf";
$subject =  "$subject_prefix $name";
$tech_admin_email = "admin@tmorg.ca";
$return_path = "admin@tmorg.ca";
$from_email = "admin@tmorg.ca";

// Measurements needed for style

// Layout scale is 1mm = 7.51 px, to have 89mm x 51mm match background image 668px x 383px.
$SC = 7.51;
$temp_n = substr_count($info, "\n") + 1;
$info_arr = explode("\n", $info);

// Because some browsers discard leading empty lines in text area
$first_line = trim($info_arr[0]);
if (empty($first_line)) $first_line .= " ";
$info_arr[0] = $first_line;
$info = implode("\n", $info_arr);

// To send to the admin, we remove empty lines
$inf_admin_arr = [];
foreach ($info_arr as $line)
{
  $line = trim($line);
  if (!empty($line)) $inf_admin_arr[] = $line;
}
$inf_admin = implode("\n", $inf_admin_arr);

// Well, we interfere a bit here
//$last_info = end($info_arr);
//if ($temp_n > 6 && $last_info != "")
//{
//  $info .= "\r\n";
//}

include ("measures.php");
//echo "n = $n<br>";

// Font scale 1pt = (.352777 * $SC)px. On the screeen, it's rounded to the nearest integer, of course.
$ftSC  = 0.352777 * $SC;

// MT font size.
$ftMTpx = $ftMT * $ftSC;
// Registred symbol font size.
$ftRgpx = $ftRg * $ftSC;
// City Province font size
$ftCPpx = $ftCP * $ftSC;
// Name font size
$ftNpx  = $ftN * $ftSC;
// Title font size
$ftTpx  = $ftT * $ftSC;
// Info font size
$ftIpx  = $ftI * $ftSC;

// Below, the bleed of 1.5 mm is removed, one side or both sides as needed.

// The page heigth
$PHpx = ($PH - 3) * $SC;
// The page width
$PWpx = ($PW - 3) * $SC + 5; // Correction +5px because not removed enoug bleed in the actual screen background image.
// The Right Margin.
$RMpx  = ($RM - 1.5) * $SC;
// The base of MT line
$bMTpx = ($bMT - 1.5)  * $SC;
// The base of City Province line
$bCPpx = ($bCP - 1.5) * $SC;
// The base of Name line
$bNpx  = ($bN - 1.5) * $SC;
// The base of Title line
$bTpx  = ($bT - 1.5) * $SC;
// The height of a line in info.
$hIpx = $hI * $SC;
// The base of info when there are ML lines.
//$bIMLpx  = ($bIML - 1.5) * $SC;
// The base of info when there are $n lines.
$bIpx  = ($bI - 1.5) * $SC;

$wrapperwidth      = 375 + $PWpx;
$cardwrapperwidth  = 357 + $PWpx;
$cardwrapperheight = 65  + $PHpx
?>


<style>
#wrapper {
  width:<?php echo $wrapperwidth ;?>px;
  margin:auto;
}


#form_card_wrapper {
  width: <?php echo $cardwrapperwidth ;?>px;
  height: <?php echo $cardwrapperheight ;?>px;
  margin: 0px 15px;
  display: block;
}

#form_wrapper{
  font-size:15px;
  line-height: 18px;
  float: left;
  width: 262px;
  padding:0px 10px 10px 10px;
}

#language_selector{
  padding-top:50px;
  padding-right: 50px;
  font-size:18px;
  float: right;
}

#infoInstruction{
  line-height:12px;
  font-size: 10px;
  padding-bottom:3px;
}

#card_info{
 padding-top: 0px;
}

#submit_info{
 padding-top: 10px;
}

#card_back {
 padding-left: 5px;
 padding-top: 7px;
}

#card_back_icon {
  float:left;
  padding-left:10px;
}

#card_back_text {
  width:150px;
  float:left;
}

#card_wrapper {
  text-align:center;
  float: left;
  background-color: #e1e1e1;
  padding: 30px 30px 10px 30px;
  width: <?php echo $PWpx ;?>px;
}

#card {
  position:relative;
  /* Because FPDF does not support kerning. The disabling of kerning is supported in Firefox and Chrome.*/
  /* So, we have MÉDIT A TION TRANSCENDANT ALE ... */
  /* TODO: Incorporate it in the background image. Kerning is more important with capitalized letters.*/
  font-kerning: none;
  background-image: url(images_card/<?php echo $logo_img;?>);
  background-repeat:no-repeat;
  height: <?php echo $PHpx ;?>px;
  width: <?php echo $PWpx ;?>px;
}

.carditem {
  position:absolute;
  font-family:"Trebuchet MS";
}


<?php
// TODO: The top of the span element is not a function of the baseline and the font-size. It depends also on the line-height in a non obvious manner. Here, if the line-height is 1.5 * font-size, we have  top = baseline - font-size, but we do not know if it is a reliable rule. If line-height = font-size, font-size = 9pt, we have top = baseline - font-size * 0.75, but this might not be reliable either.  We need to find a reliable rule.
$top = $bMTpx - $ftMTpx * 0.75;
?>

#mt {
  right: <?php echo $RMpx;  ?>px;
  top: <?php echo $top;?>px;
  font-size: <?php echo $ftMTpx; ?>px;
  line-height: <?php echo $ftMTpx; ?>px;
  color:#E59F0A;
}

#reg {
  left: <?php echo $PWpx - $RMpx  ; ?>px;
  top: <?php echo $top;?>px;
  font-size: <?php echo $ftRgpx;?>px;
  line-height: <?php echo $ftRgpx;?>px;
  color:#E59F0A;
}

<?php
$top = $bCPpx - $ftCPpx * 0.75;
?>

#city {
  right: <?php echo $RMpx;?>px;
  top: <?php echo $top;?>px;
  font-size: <?php echo $ftCPpx; ?>px;
  line-height: <?php echo $ftCPpx; ?>px;
  color:#E59F0A;
}

<?php $top = $bNpx - $ftNpx * 0.8;  ?>

#name {
  right: <?php echo $RMpx;?>px;
  top: <?php echo $top;?>px;
  font-size: <?php echo $ftNpx; ?>px;
  line-height: <?php echo $ftNpx; ?>px;
  color:#0066a4;
}

#degree {
  font-size:<?php echo $font_size * $ftSC;?>px;
}


<?php $top = $bTpx - $ftTpx * 0.75;  ?>

#title {
  right: <?php echo $RMpx;?>px;
  top: <?php echo $top;?>px;
  font-size: <?php echo $ftTpx; ?>px;
  line-height: <?php echo $ftTpx; ?>px;
  color:#0066a4;
  text-align:right;
}

<?php
// Here the line-height is always larger than the font-size. We don't use a scale factor.
$top = $bIpx - $ftIpx;
?>

#address {
  right: <?php echo $RMpx;?>px;
  top: <?php echo $top;?>px;
  font-size: <?php echo $ftIpx; ?>px;
  line-height:<?php echo $hIpx;?>px;
  color:#0066a4;
  text-align:right;
}

#address .empty {
  font-size: 0px;
  line-height:<?php echo $hIpx/4;?>px;
}

#logout {
 padding-left: 5px;
 padding-top: 7px;
}
</style>
</head>

<body>
<div id="wrapper">
<div id="wrapper_r">
<div id="header">
	<div id="header_l">
		<div id="header_r">
			<div id="<?php echo $logo_banner;?>"></div>
<!-- language_selector -->
<div id="language_selector">
<?php
switch ($lang)
{
case 'en':
?>
<a href="businesscard?fr=1" >Français</a><br />
<?php
break;
case 'fr' :
?>
<a href="businesscard" >English</a><br />
<?php
}
?>
</div>
<!-- End language_selector -->
		</div>

	</div>
</div>
<div id="tabarea">
	<div id="tabarea_l">
		<div id="tabarea_r">
			<div id="tabmenu">
        <table cellpadding="0" cellspacing="0" class="pill">
					<tbody><tr>
						<td class="pill_l">&nbsp;</td>
						<td class="pill_m">
							<div id="pillmenu">
									<ul id="mainlevel-nav"><li><a href="http://ca.tm.org" >TEACHING</a></li><li><a href="http://ca.tm.org">TM MARKETING</a></li><li><a href="<?php if ($lang=="en") echo "businesscard"; else echo "carte_affaire"; ?>" class="mainlevel-nav" id="active_menu-nav">OTHER TOOLS</a></li></ul>
							</div>
						</td>
						<td class="pill_r">&nbsp;</td>
					</tr>
				</tbody></table>
			</div>
		</div>
	</div>
</div>
<div id="form_card_wrapper">
<div id="form_wrapper">
<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" >
<!-- card_info -->
<div id="card_info">
<input type="hidden" name="lang" value="<?php echo $lang; ?>" />
City and province : <br />
<input type="text" name="city_prov"  size="20" value="<?php echo $city_prov;?>"/><br />
Name : <br />
<input type="text" name="name"  size="20" value="<?php echo $name;?>"/><br />
Degree(s):<input type="text" name="degree"  size="3" value="<?php echo $degree;?>"/>Size:<select name="font_size"><option value="5.5" <?php if($font_size == 5.5) echo "selected"; ?>>5.5</option>
<option value="6" <?php if($font_size == 6) echo "selected"; ?>>6</option>
<option value="6.5" <?php if($font_size == 6.5) echo "selected"; ?>>6.5</option>
<option value="7" <?php if($font_size == 7) echo "selected"; ?>>7</option>
<option value="7.5" <?php if($font_size == 7.5) echo "selected"; ?>>7.5</option>
<option value="8" <?php if($font_size == 8) echo "selected"; ?>>8</option>
<option value="8.5" <?php if($font_size == 8.5) echo "selected"; ?>>8.5</option>
<option value="9" <?php if($font_size == 9) echo "selected"; ?>>9</option>
<option value="9.5" <?php if($font_size == 9.5) echo "selected"; ?>>9.5</option></select>
<br />
Title : <input type="text" name="title"  size="15" value="<?php echo $title;?>"/><br />
Address, phone, email, site :
<div id="infoInstruction">
An empty line has <?php echo 100 * $FL;?>% a normal line height.  When 0.<?php echo 100 * $FL;?> * #empty +  #normal &ge; 8, the line-height gets smaller.
</div>
<textarea name="info" cols="30" rows="6"><?php echo "$info";?></textarea><br />
<input type="submit" name="update" value="Update the preview on the right" />
</div>
<!-- End card_info -->
<!-- submit_info -->
<div id="submit_info">
<input type="submit" name="submit" value="Submit" />
<span style="font-size:11pt;">Put your tm.org email below.</span><br />
<input type="text" name="email"  size="20" value="<?php echo $email;?>"/>
<?php if(! empty($_POST['submit'])) 
//**************************************************************
// Requesting the pdf to pdfforbusinesscard
//**************************************************************
{
  $ch = curl_init();
  //$lang is set above and does not need to be sanitized;
  $url_upper_city_prov = urlencode(fullUpper($city_prov));
  $url_name = urlencode($name);
  $url_degree = urlencode($degree);
  $url_font_size = urlencode($font_size); // not really needed, but safer. 
  $url_title = urlencode($title);
  $url_info = urlencode($info);
  //*********************************************
  // Security should consider all the $ch
  // possible by playing with the input  $lang, etc.
  //*********************************************

  $pdf_req = '/pdfforbusinesscard';
  curl_setopt($ch, CURLOPT_URL,"https://" . $_ENV['SERVER'] . $pdf_req);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
  curl_setopt(
  	$ch, 
  	CURLOPT_POSTFIELDS,
    "lang=$lang&".
    "city_prov=$url_upper_city_prov&" .
    "name=$url_name&" .
    "degree=$url_degree&" .
    "font_size=$url_font_size&" .
    "title=$url_title&" .
    "info=$url_info"
  );

  //*********************************************
  // receive server response ...
  $pdf_content = curl_exec ($ch);
  $error = curl_error($ch);
  curl_close ($ch);
  //*********************************************
  
//**************************************************************

  $random_hash = md5(date('r', time()));
  $separator = "PHP-mixed-".$random_hash;
  $attachment =  chunk_split(base64_encode($pdf_content));
  $eol = PHP_EOL; 	
  $from_name = "Business Card Team";
  $from = $from_email;
// main header (multipart mandatory)
  $headers  = "From: $from_name <$from>$eol";
  $headers .= "Bcc: $tech_admin_email$eol";
  $headers .= "Content-Type: multipart/mixed; boundary=\"$separator\"".$eol;
  $headers .= "MIME-Version: 1.0".$eol;
  $headers .= "Content-Transfer-Encoding: 7bit".$eol;
// message
  $msg = "--".$separator.$eol;
  $msg .= "Content-Type: text/plain; charset=\"utf-8\"".$eol;
  $msg .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
  $msg .= $name . $eol . $title . $eol . $city_prov . $eol . $inf_admin.$eol.$eol;
// attachment
  $msg .= "--".$separator.$eol;
  $msg .= "Content-Type: application/pdf; name=\"".$filename."\"".$eol;
  $msg .= "Content-Transfer-Encoding: base64".$eol;
  $msg .= "Content-Disposition: attachment".$eol.$eol;
  $msg .= $attachment.$eol.$eol;
  $msg .= "--".$separator."--".$eol;

  $to = $email ;

  mail($to,$subject,$msg,$headers,"-f $return_path");
  echo "<br>Thank you ! $note Data sent to $to.";
}
?>
</div>
<!-- End submit_info -->
</form>
<!-- card_back -->
<div id="card_back">
<a href="<?php if($lang=="fr") echo "images_card/Carte_affaire_arriere.pdf";
else echo "images_card/Business_card_back.pdf"?>">
<div id="card_back_text">
<?php if ($lang=="fr") echo "Cliquer ici pour l'arrière de la carte"; else echo "Click here to get the back of the card";?>
</div>
<div id="card_back_icon"><img src="<?php if ($lang=="fr") echo "images_card/Carte_affaire_arriere.jpg"; else echo "images_card/Business_card_back.jpg";?>" height="40"/></div>
<div style="clear:both"> </div>
</a>
</div>
<!-- End card_back -->
<!-- Logout -->
<div  id="logout">
<a href="/logout" > Logout </a>
</div>
<!-- End Logout -->
</div>
<div id="card_wrapper">
 <div id="card">
<!-- Used to test the correction factor for $top -->
<!--
  <span class="carditem" style="line-height:1px;top:<?php echo $bTpx;?>px;"> ----------------------------------------------------</span>
  <span class="carditem" style="line-height:1px;top:<?php echo $bIpx;?>px;"> ----------------------------------------------------</span>
  <span class="carditem" style="line-height:1px;top:<?php echo $bIpx - $ftIpx * 0.80;?>px;"> -----------------+++++-----------------------------------</span>
  <span class="carditem" style="line-height:1px;top:<?php echo $bIpx + ($n-1) * $hIpx;?>px;"> ----------------------------------------------------</span>
  <span class="carditem" style="line-height:1px;top:<?php echo $bNpx;?>px;"> ----------------------------------------------------</span>
  <span class="carditem" style="line-height:1px;top:<?php echo $bMTpx;?>px;"> ----------------------------------------------------</span>
  <span class="carditem" style="line-height:1px;top:<?php echo $bCPpx;?>px;"> ----------------------------------------------------</span>
-->
  <span class="carditem" id="mt" ><?php echo $MTTM;?></span>
  <span class="carditem" id="reg">&reg;</span>
  <span class="carditem" id="city" ><?php echo htmlspecialchars((fullUpper($city_prov))); ?></span>
  <div class="carditem" id="name" >
   <?php echo htmlspecialchars($name); ?><span id="degree"><?php echo htmlspecialchars($degree); ?></span>
  </div>
  <span class="carditem" id="title" ><?php echo htmlspecialchars($title); ?></span>
  <div class="carditem" id="address" >
<?php
  $n = substr_count($info, "\n") + 1;
  $br = "<br />";
  foreach($info_arr as $k => $line)
  {
    if($k == $n - 1 ) $br = "";
    $line = trim($line);
    $emptyclass = "";
    if(empty($line)) $emptyclass = "class = \"empty\" ";
    echo "<span $emptyclass>" . htmlspecialchars($line) . "$br</span>" ;
  }
?>
  </div>
 </div>
<div style="padding-top:10px;">Your business card will look as the area within the gray border.  Real size is 8,89 x 5.08 cm </div>
</div>
</div>
<div id="footer">
	<div id="footer_l">

		<div id="footer_r">
	  </div>
	</div>
</div>
</div>
</div>
 <script src="js/main.js"></script>
</body>
</html>
<?php
function stripAccents($string){
	$string = strtr_utf8($string,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
  return $string;
}

function strtr_utf8($str, $from, $to) {
    $keys = array();
    $values = array();
    preg_match_all('/./u', $from, $keys);
    preg_match_all('/./u', $to, $values);
    $mapping = array_combine($keys[0], $values[0]);
    return strtr($str, $mapping);
}

function fullUpper($string){
  return strtr(strtoupper($string), array(
      "à" => "À",
      "è" => "È",
      "ì" => "Ì",
      "ò" => "Ò",
      "ù" => "Ù",
      "á" => "Á",
      "é" => "É",
      "í" => "Í",
      "ó" => "Ó",
      "ú" => "Ú",
      "â" => "Â",
      "ê" => "Ê",
      "î" => "Î",
      "ô" => "Ô",
      "û" => "Û",
      "ç" => "Ç",
    ));
}

function sanitize_textline($line) {
   return preg_replace("/[^à-ü\p{L}\p{M}\p{Nd}\p{P}\p{Zs}]+/", "-", $line);
}

function sanitize_text($text) {
   return preg_replace("/[^à-ü\p{L}\p{M}\p{Nd}\p{P}\p{Zs}\\r\\n]+/", "-", $text);
}

