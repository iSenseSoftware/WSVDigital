<?php

/**
 * This class contains methods for retrieving, updating and displaying
 * information about a single material
 */
include('files/documents.inc.php');
include('files/target_organs.inc.php');
include('files/compositions.inc.php');
include('files/physical_hazards.inc.php');
include('files/vendors.inc.php');
include('files/gloves.inc.php');

class material {
  protected $_query;
  public $HazardEvaluationID;
  protected $_Errors = array();
  protected $_Temp = array();
  public $MaterialID;
  public $PN;
  public $Name;
  public $Description;
  public $IsNonHazardous;
  public $IsCarcinogen;
  public $IsMutagen;
  public $IsTeratogen;
  public $ChronicHazardStar;
  public $OtherChronicHealthDesc;
  public $Health;
  public $Flammability;
  public $PhysicalHazard;
  public $PPE;
  public $SpecialPPEDesc;

  public function __construct($id) {
    $this->_query = "SELECT HazardEvaluationID, MaterialID, Name, PartNumber as PN, OverallHealth as Health, OverallFlammability as Flammability, OverallPhysicalHazard as PhysicalHazard, ChronicHazardStar, IsCarcinogen, IsMutagen, IsTeratogen, SpecialPPE_Reqs as SpecialPPEDesc, Code as PPE, h.Description as Description, OtherChronicHealthDesc FROM frmHazardSummaryQuery where MaterialID = $id";
    $conn = odbc_connect('HazComDB', 'admin', 'kollani') or die('Could not connect to DB');
    $result = odbc_exec($conn, $this->_query);
    $row = odbc_fetch_array($result);
    if ($row) {
      foreach ($row as $key => $value) {
        $this->$key = $value;
        $this->_Temp[] = $value;
      }
    }

    odbc_free_result($result);
    odbc_close($conn);
    if (isset($this->HazardEvaluationID)) {
      //$this->Documents = $this->fetch_Documents($this->HazardEvaluationID);
      //$this->PhysicalHazards = $this->fetch_PhysicalHazards($this->HazardEvaluationID);
      //$this->Gloves = $this->fetch_Gloves($this->HazardEvaluationID);
    } else {
      $this->_Errors[] = "No hazard evaluation has been completed for this material!";
    }
    if (isset($this->MaterialID)) {
      //$this->Chemicals = $this->fetch_Chemicals($this->MaterialID);
      //$this->Vendors = $this->fetch_Vendors($this->MaterialID);
    } else {
      // Do nothing for now
    }
  }
  
  public function render_HMIS(){
    $output = <<<TEXT
    <div id='hmis_label'>
    <h2>HMIS Codes</h2>
    <table>
      <tr>
        <td class='H'>Health</td><td>{$this->ChronicHazardStar}</td><td>{$this->Health}</td>
      </tr>
      <tr>
        <td class='F' colspan='2'>Flammability</td><td>{$this->Flammability}</td>
      </tr>
      <tr>
        <td class='P' colspan='2'>Physical Hazard</td><td>{$this->PhysicalHazard}</td>
      </tr>
      <tr>
        <td class='PPE' colspan='2'>PPE</td><td>{$this->PPE}</td>
      </tr>
      <tr>
        <td class='PPE' colspan='3'>{$this->Description}</td>
      </tr>
    </table>
    <p><strong>Special PPE Reqs: </strong>{$this->SpecialPPEDesc}</p>
    </div>
TEXT;
    echo $output;
  }
  
  public function render_HMIS_label(){
    $output = <<<TEXT
    <div id='hmis_label'>
    <strong>{$this->MaterialID}&nbsp;&nbsp;&nbsp;&nbsp;{$this->Name}</strong>
    <table>
      <tr>
        <td class='hidden'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{$this->ChronicHazardStar}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{$this->Health}</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
      </tr>
      <tr>
        <td class='hidden' colspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{$this->Flammability}</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
      </tr>
      <tr>
        <td class='hidden' colspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{$this->PhysicalHazard}</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
      </tr>
      <tr>
        <td class='hidden' colspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>{$this->PPE}</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
      </tr>
    </table>
    <p><strong>Special PPE Reqs: </strong>{$this->SpecialPPEDesc}</p>
    </div>
TEXT;
    echo $output;
  }

  public function render_page() {
    $Documents = New documents();
    $TargetOrgans = New target_organs();
    $Compositions = New compositions();
    $PhysicalHazards = New physical_hazards();
    //$Vendors = New vendors();
    $Gloves = New gloves();
    if (empty($this->_Errors)) {
      //echo "<pre>";
       $id = <<<TEXT
      <h2><strong>Hazard Summary</strong></h2>
      <p><strong>Material ID:</strong> {$this->MaterialID}<br/>
      <strong>Name:</strong> {$this->Name}<br/>
      <strong>P/N: </strong>{$this->PN}</p>
TEXT;
      echo $id;
      $Compositions->render_table($this->MaterialID);
      $this->render_HMIS();
      echo "<button type='button' onclick='printLabel();'>Print Label</button>";
      echo "<button type='button' onclick=\"" . "window.location.href='http://huswivc0219/sheetLabel.php?id=$this->MaterialID'\"" . ">Print Label Sheet</button>";
      $PhysicalHazards->render_table($this->HazardEvaluationID);
      $TargetOrgans->render_table($this->HazardEvaluationID);
      $Gloves->render_table($this->HazardEvaluationID);
      $Documents->render_list($this->HazardEvaluationID);
      //$Vendors->render_table($this->MaterialID);
      //echo "</pre>";
    } else {
      echo "Errors Occured: \r\n\r\n";
      foreach ($this->_Errors as $err) {
        echo "$err\r\n";
      }
    }
  }

}

?>
