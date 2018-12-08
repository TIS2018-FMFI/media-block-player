<?php

include('db.php');

global $mysqli;

if(isset($_POST['action']) && !empty($_POST['action'])){

  $file = fopen("log.json", "w");
  foreach ($_POST as $key => $value) fwrite($file, $key.'='.$value);

  //echo json_encode(array("blablabla"=>"oks", "status" => "OK"));

    if(strcmp($_POST['action'], "get-avail-lang") === 0){

        $sql = "SELECT lang.id, lang.abbr, lang.name FROM lectures as l join languages as lang on lang.id = l.language_id";
        $res = array();
        if (!$mysqli->connect_errno) {
            if ($result = $mysqli->query($sql)){
              while ($row = $result->fetch_assoc()) {
                /*$obj->id = $row['id'];
                $obj->name = $row['name'];
                $obj->abbr = $row['abbr'];*/
                array_push($res, array($row['id'], $row['name'], $row['abbr']));
                //array_push($obj);
              }
            }
        }

        //array_push($res, array("status","OK"));
        fwrite($file, json_encode(array('data'=>$res, 'status'=>"OK")));

        echo json_encode(json_encode(array('data'=>$res, 'status'=>"OK")));
    }

    fclose($file);
}


?>
