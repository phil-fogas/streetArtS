<?php
declare(strict_types=1);

/* fontion pour generer lien des posts soit par sont numero id soit par son name */
function litenPost(string $page,int $id, ?string $name): string
{
    $nam= str_replace(' ', '_', $name);
 if (!$name){
    $href ='<a href="?p='.$page.'&id='.$id.'" title="street '.$id.'">street '.$id.' </a> ';
 } else {
     $href ='<a href="?p='.$page.'&id='.$nam.'" title="$name" >'.$name.' </a> ';
 }

 return $href;
}

/* fonction pour force a redimentioné
tous en gardant les porpotions des images
trop grand ppour l'aficchage html */

function redimage(string $img_src,int $dst_w=200,int $dst_h=200) : string
{
    // Lit les dimensions de l'image
    $size = @GetImageSize($img_src);
    if(!isset($size[0])){$size[0]=$dst_w;}
    if(!isset($size[1])){$size[1]=$dst_h;}
    $src_w = $size[0];
    $src_h = $size[1];

if($src_w >= $dst_h){

      $reduction = ( ($dst_w * 100) / $src_w );
      $dst_h = (int) round(( ($src_h * $reduction )/100 ),0);
    }

    if($src_w <= $dst_h){

      $reduction = ( ($dst_h * 100) / $src_h );
      $dst_w = (int) round(( ($src_w * $reduction )/100 ),0);
    }

    $imas= ' WIDTH="'.$dst_w.'" HEIGHT="'.$dst_h.'" ';

    return $imas;
}

/* crée un apercu d'un text trop long */
function apercu(string $text, int $width = 100): string
{
// $txt = explode("\n",wordwrap($text,$width,"\n"));
// if (isset($txt[1]))
    //{return $txt[0].' [...]';}
    //else
    //{return $txt[0];}

    if (strlen($text) >= $width) {
        $text = substr($text, 0, $width);
        $espace = strrpos($text, " ");
        $text = substr($text, 0, $espace);
        $text .= ' ...';
    }
    return $text;

}

/* convertion date en format Fr */
function dateFr(?string $date): ?string
{
    if ($date) {
        $dat = date('d/m/Y', strtotime($date));
    } else {
        $dat = null;
    }

    return $dat;
}

/* convertion date en format Fr avec heurre */
function dateTimeFr(?string $date): ?string
{
    if ($date) {
        $dat = date('d/m/Y H:i:s', strtotime($date));
    } else {
        $dat = null;
    }

    return $dat;
}

/* convertion date en format An */
function dateAn(string $date): string
{
    $dat = date('Y-m-d H:i:s', strtotime($date));
    return $dat;
}

/* force la redimention des images trop grande en gardant les propotions */
 function redimImage($img_Src,$max_size = 1200,$qualite = 75): bool
 {
$image_dest=$img_Src['tmp_name'];

// Récupération de l'extension de l'image
//var_dump($img_Src);

$Src=$img_Src['tmp_name'];
  $tab_ext = explode('.', $img_Src['name']);
  $extension  = strtolower($tab_ext[count($tab_ext)-1]);

  // Récupération des informations de l'image
  $image_data = getimagesize($img_Src['tmp_name']);

      // On stocke les dimensions dans des variables
    $img_width = $image_data[0];
    $img_height = $image_data[1];

    if($img_width >= $img_height){
      $new_width = $max_size;
      $reduction = ( ($new_width * 100) / $img_width );
      $new_height = (int) round(( ($img_height * $reduction )/100 ),0);
    }
    if($img_width <= $img_height){

      $new_height = $max_size;
      $reduction = ( ($new_height * 100) / $img_height );
      $new_width = (int) round(( ($img_width * $reduction )/100 ),0);
    }

    // Création de la ressource pour la nouvelle image
    $dest = imagecreatetruecolor($new_width, $new_height);

    // En fonction de l'extension on prépare l'iamge
    switch($extension){
      case 'jpg':
      case 'jpeg':
        $src = imagecreatefromjpeg($Src); // Pour les jpg et jpeg
      break;

      case 'png':
        $src = imagecreatefrompng($Src); // Pour les png
      break;

      case 'gif':
        $src = imagecreatefromgif($Src); // Pour les gif
      break;
      default:
        $src = imagecreatefromjpeg($Src);
      break;
    }

    // Création de l'image redimentionnée
    if(imagecopyresampled($dest, $src, 0, 0, 0, 0, $new_width, $new_height, $img_width, $img_height))
{
      // On remplace l'image en fonction de l'extension
      switch($extension){
        case 'jpg':
        case 'jpeg':
          imagejpeg($dest , $image_dest, $qualite); // Pour les jpg et jpeg
        break;

        case 'png':
          $black = imagecolorallocate($dest, 0, 0, 0);
          imagecolortransparent($dest, $black);

          $compression = round((100 - $qualite) / 10,0);

          imagepng($dest , $image_dest,(int)$compression); // Pour les png
        break;

        case 'gif':
          imagegif($dest , $image_dest); // Pour les gif
        break;
      }

    return true;
    }else {
    return false;
}

}

/* tranfer des photos redimention et renomée */
function transfertPhoto(array $photo): string
{

    if ($photo)
    {
        //var_dump($photo);
        $imgDirection = './img/';
       //$uploadfile = $imgDirection . basename($photo['name']);

        $type = explode('/', $photo['type']);
        $extension = $type[1];

        $validExtensions = [
            'jpg',
            'jpeg',
            'png',
        ];

        if (in_array($extension, $validExtensions))
        {
            $name = uniqid() . '.' . $extension;
            $true=redimImage($photo);

             if ($true)
            {
            move_uploaded_file($photo['tmp_name'], $imgDirection . $name);
           return $name;
            } else {
            return '';
            }

          }
    }
    return '';
}

function transfertImage(array $image): string
{
    if ($image)
    {
        //var_dump($image);
        $imgDirection = './img/';
        //$uploadfile = $imgDirection . basename($image['name']);
        $type = explode('.', $image['name']);
        $extension = $type[1];
        $name = uniqid() . '.' . $extension;
        copy($image['tmp_name'], $imgDirection.$name);
        unlink($image['tmp_name']);
         return $name;

    }

   return '';

}
/* message de bienvenue selon l'heure */
function bienvenu(): string
{
      $heure = date("H");
    if ($heure >= 6 && $heure <= 8) {
        $mess_heure = "Bon réveil";
    }
    if ($heure >= 8 && $heure <= 12) {
        $mess_heure = "Bonjour";
    }
    if ($heure >= 12 && $heure <= 13) {
        $mess_heure = "Bon appétit";
    }
    if ($heure >= 13 && $heure <= 18) {
        $mess_heure = "Bon après-midi";
    }
    if ($heure >= 18 && $heure <= 22) {
        $mess_heure = "Bonsoir";
    }
    if ($heure >= 22 || $heure <= 6) {
        $mess_heure = "Bonne nuit";
    }
    
    return $mess_heure;
}


/* calcul des distance entre deux point gps en Km*/
 function get_distance_m(float $lat1,float  $lng1,float  $lat2,float  $lng2): float
 {
      $earth_radius = 6378137;   // Terre = sphère de 6378km de rayon
      $rlo1 = deg2rad($lng1);
      $rla1 = deg2rad($lat1);
      $rlo2 = deg2rad($lng2);
      $rla2 = deg2rad($lat2);
      $dlo = ($rlo2 - $rlo1) / 2;
      $dla = ($rla2 - $rla1) / 2;
      $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin(
$dlo));
      $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
      return ($earth_radius * $d);
    }

/* trouve les point gps par adresse grace api data.gouv.fr en php */
function get_gps(string $adresse): array
{

$adresse=str_replace(' ', '+',$adresse);
$urls='https://api-adresse.data.gouv.fr/search/?q='.$adresse.'';

$result = file_get_contents($urls);

$json = json_decode($result, true);

$latitude=$json['features'][0]['geometry']['coordinates'][1];
$longitude=$json['features'][0]['geometry']['coordinates'][0];

return [$longitude,$latitude];

}

function lienGoogle(string $adresse):string
{
 $adresse=str_replace(' ', '+',$adresse);
 $lien='https://www.google.com/maps/place/'.$adresse.'';
  return $lien;
}

