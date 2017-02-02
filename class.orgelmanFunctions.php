<?php 
class orgelmanFunctions {
   public  $version                 = "dev-master";
   private $root                    = "";
   
   public function __construct($root="") {
      $this->root                   = $root;
      
   }
   public function __destruct() {
      
   }
   // Trigger an error
   private function error($message, $level=E_USER_NOTICE) { 
      $caller = debug_backtrace()[0];

      if(php_sapi_name()!='cli') {
         trigger_error($message.' in <strong>'.$caller['function'].'</strong> called from <strong>'.$caller['file'].'</strong> on line <strong>'.$caller['line'].'</strong>'."\n", $level);
      } else {
         trigger_error($message.' in '.$caller['function'].' called from '.$caller['file'].' on line '.$caller['line'], $level);
      } 
   }
   
   // Get domain, root, port and so on
   public function getDomain($root="") {
      $this->server                 = new stdClass();
      $this->server->root           = "";
      
      $this->server->domain         = "";
      $this->server->protocol       = "";
      $this->server->port           = "";
      $this->server->host           = "";
      $this->server->IP             = "";
      $this->server->server         = "";
      $this->server->URI            = "";
      $this->server->dir            = "";
      
      if($root=="") {
         if($this->root!="") {
            $root = $this->root.DIRECTORY_SEPARATOR;
         } else {
            $root = __DIR__.DIRECTORY_SEPARATOR;
         }
      } else {
         $root = $root.DIRECTORY_SEPARATOR;
      }
      $this->server->root = trim(str_replace(array("/","\\"),DIRECTORY_SEPARATOR,DIRECTORY_SEPARATOR.trim($root,"/").DIRECTORY_SEPARATOR));
      if(!file_exists($this->server->root)) {
         $this->error("No root directory found",  E_USER_ERROR);
      }
      
      
      if((isset($_SERVER['SERVER_PROTOCOL'])) && (isset($_SERVER['SERVER_PORT'])) && (isset($_SERVER['SERVER_NAME']))) {
         $ssl                       = ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' );
         $sp                        = strtolower( $_SERVER['SERVER_PROTOCOL'] );
         $this->server->protocol    = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
         $this->server->port        = $_SERVER['SERVER_PORT'];
         $port                      = ( ( ! $ssl && $_SERVER['SERVER_PORT']=='80' ) || ( $ssl && $_SERVER['SERVER_PORT']=='443' ) ) ? '' : ':'.$_SERVER['SERVER_PORT'];
         $this->server->host        = $_SERVER['SERVER_NAME'] . $port;
         $this->server->dir         = trim(str_replace($_SERVER["DOCUMENT_ROOT"],"",dirname(debug_backtrace()[0]["file"])),"/");
         

         $domain                    = explode(":",preg_replace("/^www\.(.+\.)/i", "$1", $_SERVER["HTTP_HOST"]),2);
         $server                    = parse_url($domain[0]);
         if(isset($server['host'])) {
            $host                   = strtolower($server['host']);
         } else if(isset($server['path'])) {
            $host                   = strtolower($server['path']);
         } else {
            $host                   = strtolower($_SERVER["HTTP_HOST"]);
         }
         $host_names                = explode(".", $host);
         
         if(filter_var($_SERVER["SERVER_NAME"], FILTER_VALIDATE_IP) == true) {
            $this->server->IP       = $_SERVER['SERVER_NAME'];
         } else {
            $this->server->IP       = gethostbyname($_SERVER['SERVER_NAME']);
            $this->server->server   = $host_names[count($host_names)-2] . "." . $host_names[count($host_names)-1];
         }
         
         $this->server->URI         = ltrim($_SERVER["REQUEST_URI"],"/");
         if (substr($this->server->URI, 0, strlen($this->server->dir)) == $this->server->dir) {
            $this->server->URI      = ltrim(substr(trim(trim($_SERVER["REQUEST_URI"],"/")), strlen(trim(trim($this->server->dir,"/")))),"/");
            
         } 
         
         $this->server->domain      = trim($this->server->protocol."://".$domain[0].$port."/".$this->server->dir,"/").DIRECTORY_SEPARATOR;
      }
      
      $this->server->domain         = trim($this->server->domain."/","/")."/";
      $this->server->full           = $this->server->domain.$this->server->URI;
      
      return $this->server;
   }
   
   // Clean string URL
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
      } else {
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

   // Obfuscate email
   public function obfuscate_email($email) {
      $em   = explode("@",$email);
      $name = implode(array_slice($em, 0, count($em)-1), '@');
      $len  = floor(strlen($name)/2);

      return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);
   }
   
   // Get directory size in byte
   public function folderSize($dir) {
      $count_size = 0;
      $count = 0;
      $dir_array = scandir($dir);
      foreach($dir_array as $key=>$filename){
         if($filename!=".." && $filename!="."){
            if(is_dir($dir."/".$filename)){
               $new_foldersize = foldersize($dir."/".$filename);
               $count_size = $count_size+ $new_foldersize;
            } else if(is_file($dir."/".$filename)) {
               $count_size = $count_size + filesize($dir."/".$filename);
               $count++;
            }
         }
      }
      return $count_size;
   }
   
   // Format bytes
   public function formatBytes($bytes, $precision = 2) {
      $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
      $bytes = max($bytes, 0); 
      $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
      $pow = min($pow, count($units) - 1); 

      $bytes /= pow(1024, $pow);

      return round($bytes, $precision) . ' ' . $units[$pow]; 
   } 

   // Format numbers
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
   
   // Print array to string
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
   
   // Invers RBG
   public function color_inverse($color){
      $color = str_replace('#', '', $color);
      if (strlen($color) != 6){ 
         return '000000'; 
      }
      $rgb = '';
      for ($x=0;$x<3;$x++){
         $c = 255 - hexdec(substr($color,(2*$x),2));
         $c = ($c < 0) ? 0 : dechex($c);
         $rgb .= (strlen($c) < 2) ? '0'.$c : $c;
      }
      return '#'.$rgb;
   }

   // Zip directory
   public function Zip($source, $destination) {
      if (!extension_loaded('zip') || !file_exists($source)) {
         return false;
      }

      $zip = new ZipArchive();
      if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
         return false;
      }

      $source = str_replace('\\', '/', realpath($source));

      if (is_dir($source) === true) {
         $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

         foreach ($files as $file) {
            $file = str_replace('\\', '/', realpath($file));
            if(is_dir($file) === true) {
               $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            } elseif(is_file($file) === true) {
               $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
         }
      } elseif(is_file($source) === true) {
         $zip->addFromString(basename($source), file_get_contents($source));
      }
      return $zip->close();
   }
}

?>
