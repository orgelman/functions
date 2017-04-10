<?php
   private function StartDBConnection($override=0) {
      global $DBh;
      $DBh = false;
      if($this->CORE['config']->databaseusage) {
         if((isset($DBh)) && ($DBh!="")) { 
            
         } else {
            $this->debug("Starting database connection");
            $DBh = "";
            $DBh = @mysqli_connect(SQL_HOST,SQL_USERNAME,SQL_PASSWORD,SQL_NAME) or die("Connection error: ".__LINE__);
            if (mysqli_connect_errno()) {
               $this->debug("SQL Connection error: ".__LINE__);
               die("Connection error: ".__LINE__);
            }
            if($DBh=="") {
               $this->debug("SQL Connection error: ".__LINE__);
               die("Connection error: ".__LINE__);
            }
            $this->debug("SQL connected");
         }
      }
      return $DBh;
   }
   private function StopDBConnection($DBh){
      if((isset($DBh)) && ($DBh!="")) {
         mysqli_close($DBh);
         $this->debug("SQL close connection");
      }
   }
   public function SQL($q) { 
      $arr     = array();
      $sel     = "Select";
      $prefix  = "[[DB]]";
      $caller = debug_backtrace()[0];

      if(strpos($q, $prefix) == false) {
         $this->debug("SQL missing ".$prefix." Called: ". $caller["file"]." [".$caller["line"]."]");
         $this->error("SQL missing ".$prefix." Called: ". $caller["file"]." [".$caller["line"]."]",E_USER_ERROR);
      }
      if($q!="") {
         $q = str_replace($prefix,constant("SQL_NAME")."`.`".constant("SQL_PREFIX"),$q);
      }
      if($this->CORE['config']->databaseusage) {
         if(isset($this->DBh)) {} else {
            $this->DBh = $this->StartDBConnection();
         }

         $this->debug("SQL: ".$q,1);
         $MySQLi[0]["Result"] = $this->DBh->query($q);
      
         if(strtolower(substr($q, 0, strlen($sel))) === strtolower($sel)) {
            if(!$MySQLi[0]["Result"]) {
               $this->debug("SQL ERROR: ".$q."\nSQL ERROR: ".$this->DBh->error);
               $this->error($q."<br>".$this->DBh->error,E_USER_ERROR);
            } elseif($MySQLi[0]["Result"]->num_rows>0) {
               $this->debug("SQL Returned ".$MySQLi[0]["Result"]->num_rows." rows");
            
               while($MySQLi[0]["Rows"]=$MySQLi[0]["Result"]->fetch_object()){
                  $this->killSwitch();
                  $arr[] = $MySQLi[0]["Rows"];
               }
               
               $this->debug("SQL return array");
               return $arr;
            } elseif($MySQLi[0]["Result"]->num_rows==0) {
               $this->debug("SQL Returned empty");
               return $arr;
            }
         } else {
            if(!$MySQLi[0]["Result"]) {
               $this->debug("SQL ERROR: ".$q."\nSQL ERROR: ".$this->DBh->error);
               $this->error($q."<br>".$this->DBh->error,E_USER_ERROR);
               return false;
            }
            return true;
         }
      }
      return false;
   }
   ?>
