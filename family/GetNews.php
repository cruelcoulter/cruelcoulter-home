<?php
/*
This page defines the GetNews class. It gets updated attachments and family member info from the last x months
dependencies = db_config.php and pdo.php
*/

//  This doesn't work worth a shit.
class GetNews{
  public $monthcount = 3;
  public $link;


  function GetNewPublicAttachments(){
        $querystring = "select * from attachment where access_level ='PUBLIC' and date_posted BETWEEN DATE_SUB(CURDATE(), INTERVAL ". $this->monthcount ." MONTH) AND CURDATE()";
       $result=mysqli_query($link, $querystring);
        $attachments = $result->fetchAll();
        foreach ($attachments as $attachment) :
        $returnstring = "<p><a href=\"{$attachment['attachment_location']}\">{$row['attachment_title']}</a></p>";
        endforeach;
        return $returnstring;
 }

  function GetNewPrivateAttachments(){
        $querystring = "select * from attachment where date_posted BETWEEN DATE_SUB(CURDATE(), INTERVAL ". $this->monthcount ." MONTH) AND CURDATE()";
       $result=mysqli_query($link, $querystring);
        $attachments = $result->fetchAll();
        $attachments = $querytext->fetchAll();
        foreach ($attachments as $attachment) :
        $returnstring = "<p><a href=\"{$attachment['attachment_location']}\">{$row['attachment_title']}</a></p>";
        endforeach;
        return $returnstring;
  }

}
?>
