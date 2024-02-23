<?php 
if(session_id() ==="")
session_start();
require_once('DBConnection.php');
/**
 * Login Registration Class
 */
Class Master extends DBConnection{
    function __construct(){
        parent::__construct();
    }
    function __destruct(){
        parent::__destruct();
    }
    function save_grade_tbl(){
        extract($_POST);
        $data = [];
        if(isset($grade_letter)){
            foreach($grade_letter as $k => $v){
                $data[] = "('{$v}', '{$grade_from[$k]}', '{$grade_to[$k]}', '{$scale[$k]}')";
            }
        }
        if(!empty($data)){
            $data = implode(", ", $data);
            $trucate = $this->query("DELETE FROM `grade_table`");
            $update_seq = $this->query("UPDATE `sqlite_sequence` SET `seq` = 0 WHERE `name` = 'grade_table'");
            $sql = "INSERT INTO `grade_table` (`letter_grade`, `grade_from`, `grade_to`, `scale`) VALUES {$data}";
            $insert = $this->query($sql);
            if($insert){
                $resp['status'] = 'success';
                $resp['msg'] = "GPA Grade Table has been updated successfully.";
                $_SESSION['message']['success'] = $resp['msg'];
            }else{
                $resp['status'] = 'failed';
                 $resp['msg'] = $this->lastErrorMsg();
            }
        }else{
            $resp['status'] = 'failed';
            $resp['msg'] = "There's no data sent to the request.";
        }
        return json_encode($resp);
    }
    function get_grade_tbl(){
        $sql = "SELECT * FROM `grade_table` order by `grade_to` desc, `grade_from` desc";
        $qry = $this->query($sql);
        $data = [];
        while($row = $qry->fetchArray(SQLITE3_ASSOC)){
            $row['scale'] = number_format($row['scale'], 1);
            $data[] = $row;
        }
        return json_encode($data);

    }
    function get_scale(){
        extract($_POST);
        $sql = "SELECT * FROM `grade_table` where '{$perc}' >= `grade_from` and '{$perc}' <= `grade_to`";
        $qry = $this->querySingle($sql, true);
        $data = [];
        if($qry){
            $qry['scale'] = number_format($qry['scale'], 1);
            $data = $qry;
        }
      
        return json_encode($data);

    }
}
$a = isset($_GET['a']) ?$_GET['a'] : '';
$master = new Master();
switch($a){
    case 'save_grade_tbl':
        echo $master->save_grade_tbl();
    break;
    case 'get_grade_tbl':
        echo $master->get_grade_tbl();
    break;
    case 'get_scale':
        echo $master->get_scale();
    break;
    default:
    // default action here
    break;
}