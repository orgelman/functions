<?php 
/**
 * @package orgelman/functions
 * @link    https://github.com/orgelman/functions/
 * @author  Tobias Jonson <git@orgelman.systmes>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

if(get_included_files()[0]==__FILE__){header("HTTP/1.1 403 Forbidden");die('<h1 style="font-family:arial;">Error 403: Forbidden</h1>');} 

class orgelmanSQL {
   private $DBh            = "";
   
   private $SQL_HOST       = "";
   private $SQL_USERNAME   = "";
   private $SQL_PASSWORD   = "";
   private $SQL_NAME       = "";
   private $SQL_PREFIX     = "";
   
   public function __construct($SQL_HOST,$SQL_USERNAME,$SQL_PASSWORD,$SQL_NAME,$SQL_PREFIX) {
      if(defined(SQL_HOST)) {
         $this->SQL_HOST      = constant(SQL_HOST);
      } else {
         $this->SQL_HOST      = $SQL_HOST;
         define(SQL_HOST,$this->SQL_HOST);
      }
      if(defined(SQL_USERNAME)) {
         $this->SQL_USERNAME  = constant(SQL_USERNAME);
      } else {
         $this->SQL_USERNAME  = $SQL_USERNAME;
         define(SQL_USERNAME,$this->SQL_USERNAME);
      }
      if(defined(SQL_PASSWORD)) {
         $this->SQL_PASSWORD  = constant(SQL_PASSWORD);
      } else {
         $this->SQL_PASSWORD  = $SQL_PASSWORD;
         define(SQL_PASSWORD,$this->SQL_PASSWORD);
      }
      if(defined(SQL_NAME)) {
         $this->SQL_NAME      = constant(SQL_NAME);
      } else {
         $this->SQL_NAME      = $SQL_NAME;
         define(SQL_NAME,$this->SQL_NAME);
      }
      if(defined(SQL_PREFIX)) {
         $this->SQL_PREFIX    = constant(SQL_PREFIX);
      } else {
         $this->SQL_PREFIX    = $SQL_PREFIX;
         define(SQL_PREFIX,$this->SQL_PREFIX);
      }
      
      $this->DBh = $this->StartDBConnection();
   }
   public function __destruct() {
      if(isset($this->DBh)){
         $this->StopDBConnection($this->DBh);
      }
   }
   
   private function StartDBConnection() {
      $DBh = false;
      if((isset($DBh)) && ($DBh!="")) { 
            
      } else {
         $DBh = "";
         $DBh = @mysqli_connect(SQL_HOST,SQL_USERNAME,SQL_PASSWORD,SQL_NAME) or die("Connection error: ".__LINE__);
         if (mysqli_connect_errno()) {
            die("Connection error: ".__LINE__);
         }
         if($DBh=="") {
            die("Connection error: ".__LINE__);
         }
      }
      return $DBh;
   }
   private function StopDBConnection($DBh){
      if((isset($DBh)) && ($DBh!="")) {
         mysqli_close($DBh);
      }
   }
   public function SQL($q) { 
      $arr     = array();
      $sel     = "Select";
      $prefix  = "[[DB]]";
      $caller = debug_backtrace()[0];
      if(strpos($q, $prefix) == false) {
         die("SQL missing ".$prefix." Called: ". $caller["file"]." [".$caller["line"]."]");
      }
      if($q!="") {
         $q = str_replace($prefix,"`".constant("SQL_NAME")."`.`".constant("SQL_PREFIX")."",$q);
      }
      if(isset($this->DBh)) {} else {
         $this->DBh = $this->StartDBConnection();
      }
      $MySQLi[0]["Result"] = $this->DBh->query($q);
      
      if(strtolower(substr($q, 0, strlen($sel))) === strtolower($sel)) {
         if(!$MySQLi[0]["Result"]) {
            die("SQL ERROR: ".$q."\nSQL ERROR: ".$this->DBh->error);
         } elseif($MySQLi[0]["Result"]->num_rows>0) {
            while($MySQLi[0]["Rows"]=$MySQLi[0]["Result"]->fetch_object()){
               $arr[] = $MySQLi[0]["Rows"];
            }
            return $arr;
         } elseif($MySQLi[0]["Result"]->num_rows==0) {
            return $arr;
         }
      } else {
         if(!$MySQLi[0]["Result"]) {
            die("SQL ERROR: ".$q."\nSQL ERROR: ".$this->DBh->error);
            return false;
         }
         return true;
      }
      return false;
   }
}
   ?>
