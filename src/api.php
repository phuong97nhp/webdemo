<?php
include('database.php');
$db = new Database();
$redis = new Redis();
$redis->connect('redis', 6379);

function reponse($success = true, $code = 200, $messenger = "", $data = null,  $error = null){
    $reponse = [
        'success' => $success,
        'code' => $code,
        'messenger' => $messenger ,
        'data' => $data,
        'error' => $error
    ];
    echo json_encode($reponse);
    die();
}

function get()
{
    header('Content-Type: application/json');
    global $db, $redis;
    try {
        if(!empty($valueData = $redis->get('dataTodo'))){
            reponse(true, 200, "Trả về dữ liệu thành công.", unserialize($valueData));
        }

        $getSql = "SELECT * FROM db_todo ORDER BY id DESC LIMIT 50";
        if(!empty($getData = $db->get($getSql))){
            $redis->set("dataTodo", serialize($getData));
            $redis->expire("dataTodo", 60); // thời gian hiệu lực của từng giây
            reponse(true, 200, "Trả về dữ liệu thành công.", $getData, null);
        }else{
            reponse(false, 201, "Bạn không có dữ liệu.");
        }
    } catch (\Throwable $th) {
        reponse(false, 500, 'Bạn gặp lỗi nghiêm trọng trong quá trình cập nhật dữ liệu.');
    }
}

function set()
{
    global $db, $redis;
    header("Content-Type: application/json");
    try {
        if (!empty($_POST['content']) && isset($_POST['content'])) {
            $content = (string) trim($_POST['content']); $content !== "";
        } else {
            $content = json_decode(file_get_contents('php://input'), 1);
            $content = (string) trim($content['content']); $content !== "";
        }

        if($content === "") {
            reponse(false, 405, "Dữ liệu của bạn là không hợp lệ.");
        } 

        $sql = "INSERT INTO db_todo (content, done) VALUES ('".$content."', 'done')";
        $intIdResuft = (int) $db->queryResult($sql);
        if (empty($intIdResuft)) {
            reponse(false, 202, "Cập nhật dữ liệu không thành công.");
        } else {
            $arrayData = ['id'=> $intIdResuft, 'content'=>$content, 'done'=> 'done'];
            $redis->delete('dataTodo');
            reponse(true, 200, "Thêm dữ liệu thành công.", $arrayData);
        }
    } catch (\Throwable $th) {
        reponse(false, 500, 'Bạn gặp lỗi nghiêm trọng trong quá trình cập nhật dữ liệu.');
    }
}

function delete()
{
    global $db, $redis;
    header('Content-Type: application/json');
    try {
        $intId = (int) trim($_GET['id']); !empty($intId) > 0;
        if(empty($intId))
        {
            reponse(false, 405, "Dữ liệu của bạn không hợp lệ.");
        }

        $stringSqlId = "SELECT done FROM db_todo WHERE id=$intId";
        if(empty($db->get($stringSqlId)))
        {
            reponse(false, 405, "Dữ liệu không tồn tại.");
        }

        $sql = "DELETE FROM db_todo WHERE id = $intId";
        if ($db->query($sql)) {
            $redis->delete("dataTodo");
            reponse(true, 200, "Xóa dữ liệu thành công");
        } else {
            reponse(false, 202, "Xóa dữ liệu không thành công");
        }
        
    } catch (\Throwable $th) {
        reponse(false, 500, 'Bạn gặp lỗi nghiêm trọng trong quá trình cập nhật dữ liệu.');
    }
}

function done()
{   
    global $db, $redis;
    header('Content-Type: application/json');
    try {
        $intId = (int) trim($_GET['id']); !empty($intId) > 0;
        if(empty($intId))
        {
            reponse(false, 405, "Dữ liệu của bạn không hợp lệ.");
        }

        $stringSqlId = "SELECT done FROM db_todo WHERE id=$intId";
        $stringDone = (string) $db->get($stringSqlId)[0]['done'];
        if($stringDone === "")
        {
            reponse(false, 405, "Dữ liệu không tồn tại.");
        }

        $stringDone = $stringDone == 'done'?'undone' : 'done';
        $sql = "UPDATE db_todo SET done = '$stringDone' WHERE id = $intId";
        if ($db->query($sql)) {
            $redis->delete("dataTodo");
            reponse(true, 200, "Cập nhật lại trạng thái thành công", $stringDone);
        }else {
            reponse(false, 201, "Cập nhật lại trạng thái không thành công");
        }
    } catch (\Throwable $th) {
        reponse(false, 500, 'Bạn gặp lỗi nghiêm trọng trong quá trình cập nhật dữ liệu.');
    }
}

$action = $_GET['value'];
$action();
?>