<?php
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/system_version.php';
function get_setting($key){
    global $conn;
    $stmt = $conn->prepare("SELECT value FROM setting_system WHERE `key`=? LIMIT 1");
    $stmt->bind_param('s',$key); $stmt->execute(); $res=$stmt->get_result();
    if($res && $r=$res->fetch_assoc()) return $r['value'];
    return null;
}
function catat_log($sumber,$level,$deskripsi,$detail='',$user='System'){
    global $conn;
    $stmt = $conn->prepare("INSERT INTO log_system (tanggal,sumber,level,deskripsi,detail,user) VALUES (NOW(),?,?,?,?,?)");
    $stmt->bind_param('sssss',$sumber,$level,$deskripsi,$detail,$user); $stmt->execute(); $stmt->close();
    // file log
    $dir = __DIR__ . '/../backups';
    if(!is_dir($dir)) mkdir($dir,0755,true);
    $f = __DIR__ . '/../backups/system_'.date('Y-m-d').'.log';
    $line = '['.date('Y-m-d H:i:s').'] ['.$sumber.']['.$level.'] '.$deskripsi." (user:$user)\n";
    file_put_contents($f,$line,FILE_APPEND);
}
?>