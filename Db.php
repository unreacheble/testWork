<?php
/**
 * Created by JetBrains PhpStorm.
 * User: unreacheble
 * Date: 03.06.14
 * Time: 0:42
 * To change this template use File | Settings | File Templates.
 */


class Db
{
    private $link;
    function __construct(){
        $this->link = mysql_connect( 'localhost', 'root', '' ) OR DIE ('Ошибка ДБ');
        mysql_select_db( 'rizhiyTestDb' );
        mysql_set_charset('utf8');
    }


    public function set($table, $data){
        $data = array_filter ($data );
        if(isset($data['id']) AND !empty($data['id'])){ //update
            $id = $data['id']+0;
            unset($data['id']);
            foreach( $data AS $k => $v){
                $pairs[]= "{$k} = '" . mysql_real_escape_string($v) . "'";
            }
            $query = "UPDATE {$table} SET " . implode( ', ', $pairs) . "WHERE id = {$id}";
        }else{  //insert
            $query = "INSERT INTO {$table}(" . implode( ', ', array_keys($data) ) . ")
                            VALUES ('" . implode( "', '", $data ) ."')";
        }

        mysql_query($query, $this->link) OR DIE('Query Error' . mysql_error());
        return mysql_affected_rows();
    }

    public function get($table, $where = array()){
        $query = "SELECT * FROM {$table} ";
        if(count($where) > 0){
            $whereStr = '';
           foreach($where AS $k => $v){
               if($v != ""){
                   $whereStr .= "{$k} = '" . mysql_real_escape_string($v) . "' AND ";
               }
           }
           if(!empty($whereStr)){
               $whereStr = substr( $whereStr, 0, -5 );
               $query .= " WHERE {$whereStr}";
           }
        };
        $result = mysql_query( $query ) OR DIE(mysql_error());
                   $res = array();
            while($row = mysql_fetch_assoc($result)){
                $res[] = $row;
            }
            $result = $res;
            unset($res);
        return $result;
    }
}

