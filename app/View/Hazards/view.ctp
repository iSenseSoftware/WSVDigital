<?php 
      include('/files/material.inc.php');
      $material = New material($id);
  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : Timeless   
Description: A two-column, fixed-width design with dark color scheme.
Version    : 1.0
Released   : 20110825

-->
  <script>
    function printLabel(){
      // set portrait orientation
   jsPrintSetup.setOption('orientation', jsPrintSetup.kLandscapeOrientation);
   // set top margins in millimeters
   jsPrintSetup.setOption('marginTop', 0);
   jsPrintSetup.setOption('marginBottom', 0);
   jsPrintSetup.setOption('marginLeft', 0);
   jsPrintSetup.setOption('marginRight', 0);
   // set page header
   jsPrintSetup.setOption('headerStrLeft', '');
   jsPrintSetup.setOption('headerStrCenter', '');
   jsPrintSetup.setOption('headerStrRight', '');
   // set empty page footer
   jsPrintSetup.setOption('footerStrLeft', '');
   jsPrintSetup.setOption('footerStrCenter', '');
   jsPrintSetup.setOption('footerStrRight', '');
   // clears user preferences always silent print value
   // to enable using 'printSilent' option
   jsPrintSetup.clearSilentPrint();
   // Suppress print dialog (for this context only)
   jsPrintSetup.setOption('printSilent', 0);
   // Do Print 
   // When print is submitted it is executed asynchronous and
   // script flow continues after print independently of completetion of print process! 
   jsPrintSetup.print();
   // next commands
    }
   
</script>

    <div id="hazcom_label">
      <?php $material->render_hmis_label(); ?>
    </div>
    <div id="wrapper">
      
      <div id="page">
        <div id="page-bgtop">
          <div id="page-bgbtm">
            <div id="content">
              <?php 
                $material->render_page();
              ?>
              
              <div style="clear: both;">&nbsp;</div>
            </div>
            <!-- end #content -->
            <?php //include('./sidebar.inc.php'); ?>
          </div>
        </div>
      </div>
      <!-- end #page -->
    </div>
    <?php //include('./footer.inc.php'); ?>

