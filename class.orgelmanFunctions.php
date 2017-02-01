<?php 
class orgelmanFunctions {
   public function __construct() {
      
   }
   public function __destruct() {
      
   }
   public function toAscii($str, $replace=array(), $delimiter='-') {
   if( !empty($replace) ) {
      $str = str_replace((array)$replace, ' ', $str);
   }

   $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
   $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
   $clean = strtolower(trim($clean, '-'));
   $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

   return $clean;
}

//If internet access
   public function is_connected() {
    $connected = @fsockopen("www.example.com", 80); 
    if ($connected){
        $is_conn = true; 
        fclose($connected);
    }else{
        $is_conn = false;
    }
    return $is_conn;
}

//IF remote file exists
//if(remoteFileExists("https://example.com/file.zip")) {
   public function remoteFileExists($url) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_NOBODY, true);
    $result = curl_exec($curl);
    $ret = false;
    if ($result !== false) {
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);  
        if ($statusCode == 200) {
            $ret = true;   
        }
    }
    curl_close($curl);
    return $ret;
}

   public function obfuscate_email($email)
{
    $em   = explode("@",$email);
    $name = implode(array_slice($em, 0, count($em)-1), '@');
    $len  = floor(strlen($name)/2);

    return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);
}
   public function folderSize($dir){
$count_size = 0;
$count = 0;
$dir_array = scandir($dir);
  foreach($dir_array as $key=>$filename){
    if($filename!=".." && $filename!="."){
       if(is_dir($dir."/".$filename)){
          $new_foldersize = foldersize($dir."/".$filename);
          $count_size = $count_size+ $new_foldersize;
        }else if(is_file($dir."/".$filename)){
          $count_size = $count_size + filesize($dir."/".$filename);
          $count++;
        }
   }
 }
return $count_size;
}

   public function formatNumbers($numbers, $precision = 1) { 
    $units = array('', 'K', 'M'); 
    if($numbers>999) {
      $numbers = max($numbers, 0); 
      $pow = floor(($numbers ? log($numbers) : 0) / log(1000)); 
      $pow = min($pow, count($units) - 1); 

      $numbers /= pow(1000, $pow);

      return round($numbers, $precision) . ' ' . $units[$pow];
   } else {
      return $numbers;
   }
} 
   public function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 

    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 
   public function array2str($array) {
   $str="";
   foreach($array as $k=>$i){
      if(is_array($i)){
         $str.=addslashes("<b>[".$k."]</b> => (".array2str($i).")<br> ");
      } else {
         if($i!=""){
            $str.=addslashes("[".$k."] => (".$i."), ");
         }
      }
   }
   return $str;
}
   public function color_inverse($color){
    $color = str_replace('#', '', $color);
    if (strlen($color) != 6){ return '000000'; }
    $rgb = '';
    for ($x=0;$x<3;$x++){
        $c = 255 - hexdec(substr($color,(2*$x),2));
        $c = ($c < 0) ? 0 : dechex($c);
        $rgb .= (strlen($c) < 2) ? '0'.$c : $c;
    }
    return '#'.$rgb;
}

   public function get_dir_size($directory){
   global $userDirectorySize;
    $userDirectorySize = 0;
    $files= glob($directory.'/*');
    foreach($files as $path){
      if(is_file($path)) { 
         $file_parts = pathinfo($path);
         $extensions = array('del');
         if(in_array($file_parts['extension'], $extensions)) {
         } else {
            $userDirectorySize = ($userDirectorySize+filesize($path));
         }
      } elseif(is_dir($path)) {
         get_dir_size($path);
      }
    }
    return $userDirectorySize;
    $userDirectorySize="";
} 
   public function Zip($source, $destination) {
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true)
    {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file)
        {
            $file = str_replace('\\', '/', realpath($file));

            if (is_dir($file) === true)
            {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            }
            else if (is_file($file) === true)
            {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    }
    else if (is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}
   public function obfuscate_email($email)
{
    $em   = explode("@",$email);
    $name = implode(array_slice($em, 0, count($em)-1), '@');
    $len  = floor(strlen($name)/2);

    return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);
}
   public function folderSize($dir){
$count_size = 0;
$count = 0;
$dir_array = scandir($dir);
  foreach($dir_array as $key=>$filename){
    if($filename!=".." && $filename!="."){
       if(is_dir($dir."/".$filename)){
          $new_foldersize = foldersize($dir."/".$filename);
          $count_size = $count_size+ $new_foldersize;
        }else if(is_file($dir."/".$filename)){
          $count_size = $count_size + filesize($dir."/".$filename);
          $count++;
        }
   }
 }
return $count_size;
}
}

?>
