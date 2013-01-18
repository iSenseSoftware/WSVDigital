<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of documents
 *
 * @author etbmx
 */
class documents {
 
  public function __construct() {
    
  }
  
  public function fetch_array($id){
    $arr_query = "SELECT d.DocumentID, DocumentTitle as Title, IsMSDS, Hyperlink from Documents as d inner join HazardEvaluationDocuments as hd on hd.DocumentID = d.DocumentID where HazardEvaluationID = $id";
    $conn = odbc_connect('HazComDB', 'admin', 'kollani') or die('Could not connect to DB');
    $result = odbc_exec($conn, $arr_query);
    $doc_array = array();
    while ($row = odbc_fetch_array($result)) {
      $doc_array[] = $row;
    }
    odbc_free_result($result);
    odbc_close($conn);
    return (array) $doc_array;
  }
  
  public function render_list($id){
    $document_array = $this->fetch_array($id);
    echo "<div id='document_block'>\r\n<h2>Associated Documents</h2>\r\n";
    foreach($document_array as $row){
      echo "<a href='{$row['Hyperlink']}'>{$row['Title']}</a><br/>\r\n";
    }
    echo "</div>";
  }
  
}

?>
