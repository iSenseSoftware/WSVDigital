<?php

/**
 * This is an implementation of the ActiveRecord model for database CRUD operation
 * abstraction.  All other objects extend this and inherit basic CRUD operations
 *
 * @author Josh McKenzie
 * @date 31 Jan 2012
 */
abstract class ActiveRecord {
  protected $idField;
  // the required and allowed fields arrays are formatted such that key => value
  // Corresponds to dbFieldName => FieldAlias
  // a property must be declared for each allowed variable in the extended class
  // for CRUD functionality
  protected $requiredFields = Array();
  // Note that all primary key columns must be aliased as 'id' in order for CRUD
  // operations to work correctly
  protected $allowedFields = Array();
  protected $dbName = 'Passport';
  protected $dbUser = 'mckenzie.jw';
  protected $dbPassword = 'kollani';
  protected $dbServer = "huswivc0219\SQLEXPRESS";
  protected $dbDriver = "{SQL Server Native Client 10.0}";
  protected $tableName;
  public $errors = Array();

  public function getConnection(){
    $conn = odbc_connect('Driver=' . $this->dbDriver . ';Server=' . $this->dbServer . ';Database=' . $this->dbName . ';', $this->dbUser, $this->dbPassword)
              or die('could not connect to database');
    return $conn;
  }
  
  public function closeConnection($result, $conn){
    odbc_free_result($result);
    odbc_close($conn);
  }
  
  public function __construct($id = 0){
    date_default_timezone_set('America/Los_Angeles');
    if($id==0){
      foreach($this->allowedFields as $dbField => $fieldAlias){
        $this->$fieldAlias;
      }
    } else {
      // If this is an existing record, find the matching record and populate the properties
      // from the returned record.
      $conn = odbc_connect('Driver=' . $this->dbDriver . ';Server=' . $this->dbServer . ';Database=' . $this->dbName . ';', $this->dbUser, $this->dbPassword)
              or die('could not connect to database');
      //echo 'Driver=' . $this->dbDriver . ';Server=' . $this->dbServer . ';Database=' . $this->dbName . ';', $this->dbUser, $this->dbPassword;
      $query = "SELECT * from {$this->tableName} WHERE {$this->idField}=$id";
      //echo $query;
      $results = odbc_exec($conn, $query);
      $result = odbc_fetch_array($results);
      //$this->modelId = $result['ModelID'];
      foreach($this->allowedFields as $dbField => $fieldAlias){
        $this->$fieldAlias = $result[$dbField];
        //echo $this->$fieldAlias;
      }
      odbc_free_result($results);
      odbc_close($conn);
      //var_dump($this);
    }
}
  
  public function create(){
    if($this->validate()){
      //insert new row into database
      $conn = odbc_connect('Driver=' . $this->dbDriver . ';Server=' . $this->dbServer . ';Database=' . $this->dbName . ';', $this->dbUser, $this->dbPassword)
              or die('could not connect to database');
      $query = "INSERT INTO {$this->tableName} (";
      $values = '(';
      $this->updated = date("n/d/Y g:i:s A");
      $this->added = date("n/d/Y g:i:s A");
      foreach($this->allowedFields as $dbField => $fieldAlias){
        if($this->$fieldAlias != ''){
          $query .= $dbField . ", ";
          $values .= "'" . $this->$fieldAlias . "', ";
        }
      }
      $query = trim($query, ", ") . ')';
      $values = trim($values, ', ') . ')';
      $query = $query . ' Values ' . $values;
      echo $query . "\r\n";
      odbc_exec($conn, $query) or die('fail!');
      // assign new id to the current object
      // This can be improved in the future - it is currently vulnerable to misassignment
      // due to simulataneous access i.e. if someone inserts a new row between the insertion of
      // this record and the query to find the most recent record then the wrong id will
      // be assigned.
      $nextQuery = "SELECT {$this->idField} FROM {$this->tableName} order by {$this->idField} desc";
      $nextResult = odbc_exec($conn, $nextQuery);
      $nextResult = odbc_fetch_array($nextResult);
      $this->id = $nextResult["{$this->idField}"];
      return true;
      
    } else {
      $this->errors[] = 'Could not create record.  Fields could not be validated.';
      return false;
    }
  }
  
  public function update(){
    if($this->validate()){
      //Set the updated timestamp column value to current time
      if(!isset($this->updated)){
        $this->updated = date("n/d/Y g:i:s A");
      }
      // Retrieve database connection resource
      $conn = odbc_connect('Driver=' . $this->dbDriver . ';Server=' . $this->dbServer . ';Database=' . $this->dbName . ';', $this->dbUser, $this->dbPassword)
              or die('could not connect to database');
      //Begin building the UPDATE query string
      $query = "UPDATE {$this->tableName} SET ";
      //Write all allowed Field values to the update string
      foreach($this->allowedFields as $dbField => $fieldAlias){
        if(!is_null($this->$fieldAlias) && $fieldAlias != 'id'){
          $query .= "$dbField = '{$this->$fieldAlias}', ";
        }
      }
      $query = trim($query, ", ") . " WHERE {$this->idField} = '{$this->id}'";
      echo $query . "\r\n";
      if(odbc_exec($conn, $query)){
        return true;
      }else{
        $err_msg = odbc_errormsg($conn);
        $this->errors[] = "SQL SERVER ERROR ENCOUNTERED: Msg: $err_msg";
        return false;
      }

      
    } else {
      $this->errors[] = 'Could not update record.  Fields could not be validated.';
      return false;
    }
  }
  
  public function delete(){
    $this->deleted = '1';
    $this->update();
  }
  
  public function validate(){
    // default to no validation
    return true;
  }
  
  private function logTransaction(){
    
  }
  
  public static function getTimeStamp(){
    return date("Y-m-d\TH:i:s") . substr((string)microtime(),1,8) . date("P");
  }
  
  public function fetch($orderBy = null){
   
   $conn = $this->getConnection();
   $query = "SELECT * from {$this->tableName} WHERE DELETED = 0";
   if (is_null($orderBy)){
       // do nothing
   }elseif($orderField = array_search($orderBy, $this->allowedFields)){
       
       $query .= " ORDER BY $orderField";
   }
   
   $result = odbc_exec($conn, $query);
   while($row = odbc_fetch_array($result)){
       $selectedRows[] = $row;
   }
   return $selectedRows;     
  }

  
}

?>
