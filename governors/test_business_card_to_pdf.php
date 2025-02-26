<?php
  $name = "Dominic Mayers";
  $city_prov = "Sherbrooke, Québec";
  $title = "Professeur Certifié"; 
  $info = "831 rue McGregor\r\nSherbrooke,QC,J1L 3B4\r\n(d) 819-340-914\r\n(b) 514-664-3670\r\ndmayers@tm.org\r\nwww.tm.org";
  $ch = curl_init();
  $lang=isset($_GET['lang'])?$_GET['lang']:'fr';
  
  $upper_city_prov = fullUpper($city_prov); 
  $req=strtok($_SERVER["REQUEST_URI"],'?');  
  $base = basename($req);   
  $new_req = str_replace($base, 'business_card_to_pdf.php',$req); 
  curl_setopt($ch, CURLOPT_URL,"http://".$_SERVER['HTTP_HOST']. $new_req);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,
            "lang=$lang&city_prov=$upper_city_prov&name=$name&title=$title&info=$info");

  // receive server response ...
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $pdf_content = curl_exec ($ch);
  echo curl_error($ch); 
  curl_close ($ch);
  header("Content-type:application/pdf; charset=UTF-8");
  echo $pdf_content; 
  
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

?>