<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
session_start();

class koneksi{

    public $db_host;
    public $db_user;
    public $db_pass;
    public $db_name;

    function session_validation() {
        global $_SESSION;

        if(!isset($_SESSION['sis'])) {
            die("{success: false, message: \"You are not login.\"}");
        }
    }

    public function koneksi($db_hostparam="",$db_uidparam="",$db_pwdparam="",$db_nameparam="") {
        $this->db_host = $db_hostparam;
        $this->db_user = $db_uidparam;
        $this->db_pass = $db_pwdparam;
        $this->db_name = $db_nameparam;
    }

    public function connect($bypass_session=false) {

        if (!$bypass_session) {
            $this->session_validation();
        }
        $this->conn = mysqli_connect($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
        if($this->conn){
            return $this->conn;
        }
        die('Error Connection: ' . mysqli_error($this->conn));
    }

    public function close() {
        $return = mysqli_close($this->conn);
        unset($this->conn);
        return $return;
    }

    public function array_flatten($array) {
        if (!is_array($array)) {
            return FALSE;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
              $result = array_merge($result, $this->array_flatten($value));
          }
          else {
              $result[$key] = $value;
          }
      }
      return $result;
  }

  public function query($sql, &$rec_count, &$data){
   $result = mysqli_query($this->conn,$sql);
   if ($result ) {
    $rec_count = mysqli_num_rows($result);
    $data = array();
    if ($rec_count>0) {
        while($row = mysqli_fetch_assoc($result)) {
            foreach ($row as $key => $value) {
                if ($value == "true" OR $value == "false") {
                    $row[$key] = $value == "true";
                }
            }

            array_push($data, $row);
        }
    }
    mysqli_free_result($result);
    unset($result);

    return true;
}

die('Invalid SQL command : ' . $sql . '<br><br>' . mysqli_error($this->conn));
}

public function execute($conn, $sql) {
    $result = mysqli_query($conn, $sql);

    if ($result == 0) {
        die('Invalid SQL command : ' . $sql . '<br><br>' . mysqli_error($this->conn));
    } else {
        return mysqli_affected_rows($conn);
    }
}

public function SQLupdateData($table, $dataArr, $cozArr, $escapequote='') {

   $query = "UPDATE $table SET " ;
   foreach ($dataArr as $item => $value) {
    $query .= "" . $item . "=" . ($item == $escapequote ? "$value" : "'$value'") . ", ";
}

$sql = rtrim($query,', ')." WHERE 1 ";
foreach ($cozArr as $item => $value) {
    $sql .= " AND $item='$value'";
}

return $sql;	
}	

public function SQLinsertData($table, $dataArr, $escapequote='') {
   $query = "INSERT INTO $table SET ";
   foreach ($dataArr as $item => $value) {
    $query .= "" . $item . "=" . ($item == $escapequote ? "$value" : "'$value'") . ", ";
}

return rtrim($query,', ');
}

public function getLastNumber($table, $field, $length=0, $prefix='', $suffix='', $seleksi=array(), $kecuali=array()) {

    $sql = "SELECT MAX(CONVERT(SUBSTRING($field, LENGTH('$prefix')+1, LENGTH($field)-LENGTH('$prefix')-LENGTH('$suffix')), SIGNED))+1
    AS LAST FROM $table WHERE 1 AND LEFT($field, LENGTH('$prefix'))='$prefix' AND RIGHT($field, LENGTH('$suffix'))='$suffix'";
    foreach($seleksi as $item => $value) {
        $sql .= " AND $item";
        if(is_array($value)) {
            $sql .= "IN (";
            foreach($value as $items => $values) {
                $sql .= (array_search($item, $escapequote)!==false?"$values":"'$values'").",";
            }
            $sql = rtrim($sql, ",").")";

        } else {
            $sql .="=".(array_search($item, $escapequote)!==false?"$value":"'$value'");
        }
    }

    foreach($kecuali as $item => $value) {
        $sql .= " AND $item";
        if(is_array($value)) {
            $sql .= "NOT IN (";
            foreach($value as $items => $values) {
                $sql .= (array_search($item, $escapequote)!==false?"$values":"'$values'").",";
            }
            $sql = rtrim($sql, ",").")";

        } else {
            $sql .="<>".(array_search($item, $escapequote)!==false?"$value":"'$value'");
        }
    }
    $this->query($sql, $rs_num, $rs);

    $last = isset( $rs[0]["LAST"])?$rs[0]["LAST"]:1;
    $length-=strlen($last);
    for($i=0; $i<$length; $i++) $last="0".$last;

        return $prefix.$last.$suffix;
}

public function isDuplicateId($cekId, $tabel, $kolomCekId, $exeptId="", $kolomExeptId="") {
    $kolomExeptId=strlen($kolomExeptId)==0?$kolomCekId:$kolomExeptId;
    $sql = "SELECT COUNT($kolomCekId) AS TOTAL FROM $tabel WHERE $kolomCekId='$cekId' AND $kolomExeptId<>'$exeptId'" ;
    $this->query($sql, $rs_num, $rs);

    return $rs[0]["TOTAL"]>0;
}

public function getFieldValue($tabel, $kolom=array(), $seleksi=array(), $escapequote=array()) {

    $sql = "SELECT ";
    foreach ($kolom as $key => $value) {
        $sql.="`$value`,";
    }
    $sql=rtrim($sql,","). " FROM $tabel WHERE 1";

    foreach($seleksi as $item => $value) {
        $sql .= " AND $item";
        if(is_array($value)) {
            $sql .= "IN (";
            foreach($value as $items => $values) {
                $sql .= (array_search($item, $escapequote)!==false?"$values":"'$values'").",";
            }
            $sql = rtrim($sql, ",").")";

        } else {
            $sql .="=".(array_search($item, $escapequote)!==false?"$value":"'$value'");
        }
    }

    $this->query($sql, $rs_num, $result);

    return $result;
}
}