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
  <?php 
    include('files/materials.inc.php');
    if(isset($_GET['sort_by'])){
      $order_by = $_GET['sort_by'];
    }else {
      $order_by = '';
    }
    $materials = New materials('',$order_by);
    //echo $order_by;
  ?>
  <script>
    var tableArray;
    $(document).ready(function() 
    { 
        tableArray = tableToArray('chemical_list');
        $("table").tablesorter(); 
        //tableToArray('chemical_list');
        //alert('completed');
    } 
); 
  </script>
  <body>
    <div id="wrapper">
      <?php //include('./header.inc.php'); ?>
      
      <div id="page">

        <div id="chem_content">
          
          <div class="post">
            <h2 class="title">On-Site Chemicals</h2>
            <div class="entry">
              <p>Below is a complete list of all chemicals present on the WSV site.  There are links in the table
                to the MSDS for each chemical as well as links to the complete hazard evaluation summaries for each material.</p>
              <div id="filter_bar">
                <span>Filter:</span>
                <input type="text" id="filterBox" onkeyup="applyFilter('chemical_list', $('#filterBox').val(), tableArray);"></input>
              </div>
          </div>
          <div style="clear: both;">&nbsp;</div>
          <!-- Start the table for the chemical hazard list -->
          <?php $materials->render_table(); ?>

        </div>
        <!-- end #content -->
        <?php //include('./sidebar.inc.php'); ?>


      </div>
      <!-- end #page -->

    </div>
    <?php
    //include('./footer_links.inc.php');
    //include('./footer.inc.php');
    ?>
  </body>
</html>
