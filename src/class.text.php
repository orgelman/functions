<?php 
/**
 * @package orgelman/functions
 * @link    https://github.com/orgelman/functions/
 * @author  Tobias Jonson <git@orgelman.systmes>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

if(get_included_files()[0]==__FILE__){header("HTTP/1.1 403 Forbidden");die('<h1 style="font-family:arial;">Error 403: Forbidden</h1>');} 

class orgelmanText {
   private $key           = "asdyfhasugfwauisOUGSAD8hdfaegu";
   private $secondKey     = "ajlgsd97asd";
   
   private $replaceNew    = array("Bi01010000NA","Bi00101101NA","Bi01010011NA","Bi01000010NA","Bi00100000NA","Bi00100110NA","Bi00111011NA","Bi00111010NA","Bi00111101NA","Bi00100010NA","Bi00100111NA","Bi01100000NA","Bi00100101NA","Bi00100100NA");
   private $replaceOld    = array("+"           ,"-"           ,"/"           ,"\\"          ," "           ,"&"           ,";"           ,":"           ,"="           ,'"'           ,"'"           ,"`"           ,"%"           ,"$"           );
   private $saltMaxLength = 255;
   
   private $debug         = false;
   
   public function __construct() {
      $this->key        = $this->md5hash($this->key);
      $this->secondKey  = $this->md5hash($this->secondKey);
   }
   
   public function set_debug($bool) {
      $this->debug = $bool;
   }
   public function set_key($str) {
      $this->key        = $this->md5hash($str);
   }
   public function set_secondKey($str) {
      $this->secondKey  = $this->md5hash($str);
   }
   
   
   
   public function saveText_strip($str) {
      $str = str_replace(array("\n","\r"), array("",""), $str);
      $str = str_replace(array("<br>","<br />","<hr>","<hr />"), array("<br>","<br>","<hr>","<hr>"), $str);

      return $this->saveText(strip_tags($str,'<br><hr>'));
   }
   public function saveText_stripStand($str) {
      $str = str_replace(array("\n","\r"), array("",""), $str);
      $str = str_replace(array("<br>","<br />","<hr>","<hr />"), array("<br>","<br>","<hr>","<hr>"), $str);
      return $this->saveText(strip_tags($str,"<div><br><hr><ul><ol><li><p><h1><h2><h3><h4><h5><h6><small><sub><sup><s><i><b><strong><u><s><a><img><blockquote><cite><table><tbody><thead><tfoot><tr><td><th>"));
   }
   public function saveText_stripFull($str,$newline=0) {
      if(is_array($str)) {
         $new = "";
         foreach($str as $st) {
            $new .= " - ".$st;
         }
         $str = $new;
      }
      return $this->saveText(strip_tags($str),0,$newline);
   }
   public function saveText($str) {
      $taglist             = array("");
      $str                 = str_replace($this->replaceNew,$this->replaceOld,$str);
      $str                 = str_replace(array("\n","\r","|"),array("<br>\n","","&#124;"),html_entity_decode(html_entity_decode($str)));
      $str                 = str_replace(array("<br>","<br />","<hr>","<hr />"), array("<br>","<br>","<hr>","<hr>"), $str);
      $str                 = preg_replace('/\s\s+/', ' ',$str);
      
      preg_match_all("/<(\/|)([a-zA-Z1-9]{1,})(.*?)([a-zA-Z1-9]|\/| |'|\")>/xi", $str, $output_array);
      foreach($output_array[0] as $tag) {
         $id                  = str_replace("0","O",strtoupper("T".uniqid()."T"));
         $taglist[$id]        = $tag;
         $str                 = str_replace($tag,$id,$str);
      }
      $str              = addslashes(htmlentities($str));
      
      $str2             = "";
      foreach($taglist as $id => $tag) {
         if(($tag!="") && ($id!="")) {
            $str2         .= addslashes(htmlentities($id))."/TA()XYX()GG/".addslashes(htmlentities($tag))."|";
         }
      }
      
      $str              = $this->encrypt(utf8_encode(preg_replace('!\s+!', ' ', str_replace(array("\n","\r"),"",$str ))),"savetextfi","savetextfi");
      if($str2!="") {
         $str2          = $this->encrypt(utf8_encode(preg_replace('!\s+!', ' ', str_replace(array("\n","\r"),"",$str2))),"savetextse","savetextse");
      }
      
      return $this->encrypt($str."|".$str2,"savingtext","savingtext");
   }
   public function loadText($str) {
      $str = $this->decrypt($str,"savingtext");
      if(strpos($str, '|') !== false) {
         $tags          = explode("|",stripslashes($str));
         $str           = str_replace("&amp;","&",$this->decrypt($tags[0],"savetextfi"));
         $tag           = str_replace("&amp;","&",$this->decrypt($tags[1],"savetextse"));
                  
         $tags          = explode("|",stripslashes($tag));
         
         if(isset($tags[0])) {
            foreach($tags as $n => $tag) {
               if(strpos($tag, '/TA()XYX()GG/') !== false) {
                  $replace = explode("/TA()XYX()GG/",$tag);
                  
                  $str     = str_replace($replace[0],html_entity_decode($replace[1]),$str);
               }
            }
         }
      }
      $str             = preg_replace('!\s+!', ' ', $str);
      
      return $this->shortCode($str);
   }
   public function shortCode($str="",$over=false) {
      $debug = $this->debug;
      if($over) {
         $debug = false;
      }
      if(($str!="") && (!$debug)) {
         $str = preg_replace("/(console.(...)\((\"|\')(.*)(\"|\'))\);/ix", "", $str);
         $str = preg_replace("/<!--[^[if][^<![](.|\s)*?-->/", "", $str);
         $str = preg_replace('!/\*.*?\*/!s', '', $str);
         $str = preg_replace('/\n\s*\n/', "\n", $str);
         $str = preg_replace('/\s+/', ' ',str_replace(array("  ","\n","\r"),array(" ","",""),$str));
            
         $str = preg_replace("/>\s*</isx", "><", $str);
         $str = preg_replace("/;\s*/isx", ";", $str);
         $str = preg_replace("/(\s*\{\s*)/isx", "{", $str);
         $str = preg_replace("/(\s*\}\s*)/isx", "}", $str);
         $str = preg_replace("/\/\/?\s*\*[\s\S]*?\*\s*\/\/?/ix", "",$str);
         $str = str_replace(array("\n","\r","console.log(obj);","console.log(data);"),"",preg_replace("/\s{2,}/", ' ',$str));
            
         $old= array('( ',' )','function ()',') {',', funct','if (','if(! ',' == ',' === '," != "," !== ",'", "',"', '",'(! ');
         $new= array('(' ,')' ,'function()' ,'){' ,',funct' ,'if(' ,'if(!' ,'=='  ,'==='  ,"!="  ,"!=="  ,'","' ,"','" ,'(!' );
           
         $str = str_replace($old,$new,$str);
         $str = preg_replace("/^\s/", '',$str);
      }
      return stripslashes($str);
   }
   
   
   public function md5hash($str) {
      return str_replace($this->replaceOld,$this->replaceNew,md5($str));
   }
   
   public function encryptEmail($email) {
      return $this->encrypt(trim($email), $this->secondKey,"cryptEmail");
   }
   public function decryptEmail($emailhash) {
      if (strpos($emailhash,'@') !== false) {
         return $emailhash;
      } else {
         return $this->decrypt($emailhash, $this->secondKey);
      }
   } 
   
   public function encrypt($string, $key="", $prekey="") {
      if($key=="") {
         $key = $this->secondKey;
      }
      $prekey  = str_replace(" ","",$prekey);
      $key     = str_replace(" ","",$key);
      $u = substr(substr($this->toAscii(md5(uniqid())), 0, (10-strlen(substr($this->toAscii($prekey), 0, 10)))).substr($this->toAscii($prekey), 0, 10), 0, 10);
      
      if($string!="") {
         return trim("_!--_".$u."_".str_replace($this->replaceOld,$this->replaceNew,base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($u.$key), trim($string), MCRYPT_MODE_CBC, md5(md5($u.$key)))))."_--!_");
      } else {
         return $string;
      }
   }
   public function decrypt($encrypted, $key="") {
      if($key=="") {
         $key = $this->secondKey;
      }
      $decrypt = explode("_",$encrypted);
      
      if(($encrypted!="") && (((substr($encrypted,0,5) == "_!--_") && (substr($encrypted,15,1) == "_") && (substr($encrypted,-5) == '_--!_')))) {
         $dec = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($decrypt[2].$key), base64_decode(str_replace($this->replaceNew,$this->replaceOld,trim($decrypt[3]))), MCRYPT_MODE_CBC, md5(md5($decrypt[2].$key))), "\0");
         return $dec;
      } else {
         return $encrypted;
      }
   }
   public function generateHash($password, $user) {
      $user = strtolower($user);
      if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
         $salt = '$2y$11$' . substr(md5(uniqid(rand(), true)), 0, $this->saltMaxLength);
         $hash = crypt($password, $salt);
         $hash = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($this->key), $hash, MCRYPT_MODE_CBC, md5(md5($this->key))));
         $hash = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($this->secondKey), $hash, MCRYPT_MODE_CBC, md5(md5($this->secondKey))));
         $hash = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($user), $hash, MCRYPT_MODE_CBC, md5(md5($user))));
         return $this->encrypt($hash,$user,"safestpass");
      }
   }
   public function verifyHash($password, $hashedPassword, $user) {
      $user = strtolower($user);
      $hashedPassword = $this->decrypt($hashedPassword,$user);
      $hashedPassword = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($user), base64_decode($hashedPassword), MCRYPT_MODE_CBC, md5(md5($user))), "\0");
      $hashedPassword = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($this->secondKey), base64_decode($hashedPassword), MCRYPT_MODE_CBC, md5(md5($this->secondKey))), "\0");
      $hashedPassword = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($this->key), base64_decode($hashedPassword), MCRYPT_MODE_CBC, md5(md5($this->key))), "\0");
      return crypt($password, $hashedPassword) == $hashedPassword;
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
}
