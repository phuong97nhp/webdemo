<?php
include('database.php');
$db = new Database();
$redis = new Redis();
$redis->connect('redis', 6379);

function get()
{
    header('Content-Type: application/json');
    global $db, $redis;
    try {
        if(!empty($valueData = $redis->get('dataTodo'))){
            $reponse = [
                'success' => true,
                'code' => 200,
                'messenger' => "Trả về dữ liệu thành công.",
                'data' => unserialize($valueData),
                'error' => null
            ];
            echo json_encode($reponse);
            die();
        }

        $getSql = "SELECT * FROM db_todo WHERE 1 ORDER BY id DESC";
        if(!empty($getData = $db->get($getSql))){
            $reponse = [
                'success' => true,
                'code' => 200,
                'messenger' => "Trả về dữ liệu thành công.",
                'data' => $getData,
                'error' => null
            ];
            $redis->set("dataTodo", serialize($getData));
            $redis->expire("dataTodo", 60); // thời gian hiệu lực của từng giây
        }else{
            $reponse = [
                'success' => false,
                'code' => 201,
                'messenger' => "Bạn không có dữ liệu.",
                'data' => null,
                'error' => null
            ];
        }
    } catch (\Throwable $th) {
        $reponse = [
            'success' => false,
            'code' => 500,
            'messenger' => 'Bạn gặp lỗi nghiêm trọng trong quá trình cập nhật dữ liệu',
            'data' => null,
            'error' => null
        ];
    }
    echo json_encode($reponse);
    die();
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
            $reponse = [
                'success' => false,
                'code' => 405,
                'messenger' => "Dữ liệu của bạn là không hợp lệ.",
                'data' => null,
                'error' => null
            ];
            echo json_encode($reponse);
            die();
        } 

        $sql = "INSERT INTO db_todo (content, done) VALUES ('".$content."', 'done')";
        $getSql = "SELECT * FROM db_todo WHERE 1 ORDER BY id DESC";
        if (!$db->query($sql)) {
            $valueData = $redis->get('dataTodo');
            $reponse = [
                'success' => false,
                'code' => 202,
                'messenger' => "Cập nhật dữ liệu không thành công.",
                'data' => $valueData,
                'error' => null
            ];
            echo json_encode($reponse);
            die();
        } else {
            $reponse = [
                'success' => true,
                'code' => 201,
                'messenger' => "Thêm dữ liệu thành công.",
                'data' => $db->get($getSql),
                'error' => null
            ];
            $redis->delete('dataTodo');
        }
    } catch (\Throwable $th) {
        $reponse = [
            'success' => false,
            'code' => 500,
            'messenger' => 'Trạng thái lỗi.',
            'data' => null,
            'error' => null
        ];
    }
    echo json_encode($reponse);
    die();
}

function delete()
{
    global $db, $redis;
    header('Content-Type: application/json');
    try {
        $id = (int) trim($_GET['id']); !empty($id) > 0;
        if(empty($id))
        {
            $reponse = [
                'success' => false,
                'code' => 405,
                'messenger' => "Dữ liệu của bạn không hợp lệ.",
                'data' => null,
                'error' => null
            ];
            echo json_encode($reponse);
            die();
        }

        $checkSqlId = "SELECT done FROM db_todo WHERE id=$id";
        if(empty($db->get($checkSqlId)))
        {
            $reponse = [
                'success' => false,
                'code' => 405,
                'messenger' => "Dữ liệu không tồn tại.",
                'data' => null,
                'error' => null
            ];
            echo json_encode($reponse);
            die();
        }

        $sql = "DELETE FROM db_todo WHERE id = $id";
        if ($db->query($sql)) {
            $reponse = [
                'success' => true,
                'code' => 200,
                'messenger' => "Xóa dữ liệu thành công",
                'data' => null,
                'error' => null
            ];
            $redis->delete("dataTodo");
        } else {
            $reponse = [
                'success' => false,
                'code' => 202,
                'messenger' => "Xóa dữ liệu không thành công",
                'data' => null,
                'error' => null
            ];
        }
        
    } catch (\Throwable $th) {
        $reponse = [
            'success' => false,
            'code' => 500,
            'messenger' => 'Trạng thái lỗi.',
            'data' => null,
            'error' => null
        ];
    }
    echo json_encode($reponse);
    die();
}

function done()
{   
    global $db, $redis;
    header('Content-Type: application/json');
    try {
        $id = (int) trim($_GET['id']); !empty($id) > 0;
        if(empty($id))
        {
            $reponse = [
                'success' => false,
                'code' => 405,
                'messenger' => "Dữ liệu của bạn không hợp lệ.",
                'data' => null,
                'error' => null
            ];
            echo json_encode($reponse);
            die();
        }

        $stringSqlId = "SELECT done FROM db_todo WHERE id=$id";
        $stringDone = (string) $db->get($stringSqlId)[0]['done'];
        if($stringDone === "")
        {
            $reponse = [
                'success' => false,
                'code' => 405,
                'messenger' => "Dữ liệu không tồn tại.",
                'data' => null,
                'error' => null
            ];
            echo json_encode($reponse);
            die();
        }

        $stringDone = $stringDone == 'done'?'undone' : 'done';
        $sql = "UPDATE db_todo SET done = '$stringDone' WHERE id = $id";
        if ($db->query($sql)) {
            $reponse = [
                'success' => true,
                'code' => 200,
                'messenger' => "Cập nhật lại trạng thái thành công",
                'data' => null,
                'error' => null,
                'done' => $stringDone
            ];
            $redis->delete("dataTodo");
        }else {
            $reponse = [
                'success' => false,
                'code' => 201,
                'messenger' => "Cập nhật lại trạng thái không thành công",
                'data' => null,
                'error' => null
            ];
        }
    } catch (\Throwable $th) {
        $reponse = [
            'success' => false,
            'code' => 500,
            'messenger' => 'Trạng thái lỗi.',
            'data' => null,
            'error' => null
        ];
    }
    echo json_encode($reponse);
    die();
}

$action = $_GET['value'];
$action();
?>