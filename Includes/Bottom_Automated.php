
<?php
  //Assign all Page Specific variables
  $pagemaincontent = ob_get_contents();
  ob_end_clean();
  $pagetitle = "Page Specific Title Text";
  include( $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/master_Automated.php");
  //Apply the template
?>