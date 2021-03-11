<?php
include('database.php');
$db = new Database();

function get()
{
    global $db;
    header('Content-Type: application/json');
    try {
        $getSql = "SELECT * FROM db_todo WHERE 1";
        $getData = $db->get($getSql);
        if(!empty($getData)){
            $reponse = [
                'success' => true,
                'code' => 200,
                'messenger' => "Tra ve du lieu thanh cong",
                'data' => array_reverse($getData),
                'error' => null
            ];
        }else{
            $reponse = [
                'success' => false,
                'code' => 201,
                'messenger' => "Du lieu cua ban bi rong",
                'data' => null,
                'error' => null
            ];
        }
    } catch (\Throwable $th) {
        $reponse = [
            'success' => false,
            'code' => 500,
            'messenger' => 'He thong tra ve loi',
            'data' => null,
            'error' => null
        ];
    }
    echo json_encode($reponse);
    die();
}

function set()
{
    global $db;
    header("Content-Type: application/json");
    try {
        if (!empty($_POST['content']) && isset($_POST['content'])) {
            $content = (string) $_POST['content'];
        } else {
            $content = json_decode(file_get_contents('php://input'), 1);
            $content = (string) $content['content'];
        }
        if(!empty(trim($content)))
        {
            // $sql = "INSERT INTO db_todo (content, done) VALUES ($content, 'done')";
            $sql = "INSERT INTO db_todo (content, done) VALUES ('".$content."', 'done')";
            $getSql = "SELECT * FROM db_todo WHERE 1";
            if ($db->query($sql)) {
                $reponse = [
                    'success' => true,
                    'code' => 201,
                    'messenger' => "Thêm dữ liệu thành công.",
                    'data' => array_reverse($db->get($getSql)),
                    'error' => null
                ];
            }else{
                $reponse = [
                    'success' => false,
                    'code' => 202,
                    'messenger' => "Cap nhat du lieu khong thanh cong.",
                    'data' => array_reverse($db->get($getSql)),
                    'error' => null
                ];
            }
        }else {
            $reponse = [
                'success' => false,
                'code' => 405,
                'messenger' => "Du lieu cua ban khong hop le.",
                'data' => null,
                'error' => null
            ];
        } 
    } catch (\Throwable $th) {
        $reponse = [
            'success' => false,
            'code' => 500,
            'messenger' => 'He thong tra ve loi',
            'data' => null,
            'error' => null
        ];
    }
    echo json_encode($reponse);
    die();
}

function delete()
{
    global $db;
    header('Content-Type: application/json');
    try {
        $id = (int) trim($_GET['id']); !empty($id) > 0;
        if(!empty($id))
        {
            $sql = "DELETE FROM db_todo WHERE id = $id";
            if ($db->query($sql)) {
                $reponse = [
                    'success' => true,
                    'code' => 200,
                    'messenger' => "Xóa dữ liệu thành công",
                    'data' => null,
                    'error' => null
                ];
            } else {
                $reponse = [
                    'success' => false,
                    'code' => 202,
                    'messenger' => "Khong the xoa du lieu cua ban",
                    'data' => null,
                    'error' => null
                ];
            }
        }else{
            $reponse = [
                'success' => false,
                'code' => 405,
                'messenger' => "Du lieu cua ban khong hop le.",
                'data' => null,
                'error' => null
            ];
        }
        
    } catch (\Throwable $th) {
        $reponse = [
            'success' => false,
            'code' => 500,
            'messenger' => 'He thong tra ve loi',
            'data' => null,
            'error' => null
        ];
    }
    echo json_encode($reponse);
    die();
}

function done()
{   
    global $db;
    header('Content-Type: application/json');
    try {
        $id = (int) trim($_GET['id']); !empty($id) > 0;
        if(!empty($id))
        {
            $getSql = "SELECT * FROM db_todo WHERE id = $id";
            $arrData = $db->get($getSql);
            if (count($arrData)==1) {
                if($arrData[0]['done'] === 'done'){
                    $done = 'undone';
                }else{
                    $done = 'done';
                }
                $sql = "UPDATE db_todo SET done='".$done."' WHERE id = $id";
                if ($db->query($sql)) {
                    $reponse = [
                        'success' => true,
                        'code' => 200,
                        'messenger' => "Cập nhật lại trạng thái thành công",
                        'data' => null,
                        'error' => null,
                        'done' => $done
                    ];
                }else {
                    $reponse = [
                        'success' => false,
                        'code' => 201,
                        'messenger' => "Trang thai ban cap nhat khong thanh cong",
                        'data' => null,
                        'error' => null
                    ];
                }
            }else{
                $reponse = [
                    'success' => false,
                    'code' => 405,
                    'messenger' => "Du lieu cua ban khong ton tai.",
                    'data' => null,
                    'error' => null
                ];
            }
            
        }else{
            $reponse = [
                'success' => false,
                'code' => 404,
                'messenger' => "Du lieu cua ban khong hop le.",
                'data' => null,
                'error' => null
            ];
        }
    } catch (\Throwable $th) {
        $reponse = [
            'success' => false,
            'code' => 500,
            'messenger' => 'He thong tra ve loi',
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