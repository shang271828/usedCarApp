<?php

require 'conn.php';
header('Content-Type:text/html;charset=utf-8');

// 替换HTML尾标签,为过滤服务
//--------------------------
function lib_replace_end_tag($str) {
    if (empty($str))
        return false;
    $str = htmlspecialchars($str);
    $str = str_replace('/', "", $str);
    $str = str_replace("\\", "", $str);
    $str = str_replace("&gt", "", $str);
    $str = str_replace("&lt", "", $str);
    $str = str_replace("<SCRIPT>", "", $str);
    $str = str_replace("</SCRIPT>", "", $str);
    $str = str_replace("<script>", "", $str);
    $str = str_replace("</script>", "", $str);
    $str = str_replace("select", "select", $str);
    $str = str_replace("join", "join", $str);
    $str = str_replace("union", "union", $str);
    $str = str_replace("where", "where", $str);
    $str = str_replace("insert", "insert", $str);
    $str = str_replace("delete", "delete", $str);
    $str = str_replace("update", "update", $str);
    $str = str_replace("like", "like", $str);
    $str = str_replace("drop", "drop", $str);
    $str = str_replace("create", "create", $str);
    $str = str_replace("modify", "modify", $str);
    $str = str_replace("rename", "rename", $str);
    $str = str_replace("alter", "alter", $str);
    $str = str_replace("cas", "cast", $str);
    $str = str_replace("&", "&", $str);
    $str = str_replace(">", ">", $str);
    $str = str_replace("<", "<", $str);
    $str = str_replace(" ", chr(32), $str);
    $str = str_replace(" ", chr(9), $str);
    $str = str_replace("    ", chr(9), $str);
    $str = str_replace("&", chr(34), $str);
    $str = str_replace("'", chr(39), $str);
    $str = str_replace("<br />", chr(13), $str);
    $str = str_replace("''", "'", $str);
    $str = str_replace("css", "'", $str);
    $str = str_replace("CSS", "'", $str);

    return $str;
}

$action = $_GET['action'];
switch ($action) {

    //注册会员
    case"adduserinfo";
        $username = lib_replace_end_tag(trim($_GET['username']));
        $password2 = lib_replace_end_tag(trim($_GET['userpassword']));
        $password = md5("$password2" . ALL_PS);
        $email = lib_replace_end_tag(trim($_GET['email']));

        if ($username == '' || $password2 == '' || $password == '') {
            $res = urlencode("参数有误");
            exit(json_encode($res)); //有空信息
        }

        $sql = "select username from `members` where username='$username'";
        $query = mysql_query($sql, $conn);
        $count = mysql_num_rows($query);

        if ($count > 0) {
            exit(json_encode(1)); //返回1表示注册失败
        } else {

            $addsql = "insert into `member` (username,password,email) values ('$username','$password','$email')";
            mysql_query($addsql);
            exit(json_encode(0)); //返回0表示注册成功
        }
        break;


    //查询用户信息
    case"selectuserinfo";
        $username = lib_replace_end_tag($_GET['username']);
        $sql = "select uid,username,nickname,name,duty from `members` where username='$username'";
        $query = mysql_query($sql, $conn);
        $row = mysql_fetch_array($query);
        foreach ($row as $key => $v) {
            $res[$key] = urlencode($v);
        }
        exit(json_encode($res));
        break;


    //会员登录
    case"userlogin";
        $username = lib_replace_end_tag($_GET['username']);
        $password2 = lib_replace_end_tag(trim($_GET['userpassword']));
        $password = md5("$password2" . ALL_PS);
        $sqluser = "select id,username,password from `members` where username='" . $username . "' and password='" . $password . "'";
        $queryuser = mysql_query($sqluser);
        $rowuser = mysql_fetch_array($queryuser);
        if ($rowuser && is_array($rowuser) && !empty($rowuser)) {
            if ($rowuser['username'] == $username && $rowuser['password'] == $password) {
                if ($rowuser['password'] == $password) {
                    $res = urlencode("登录成功");
                    exit(json_encode($res));
                } else {
                    $res = urlencode("密码错误");
                    exit(json_encode($res));
                }
            } else {
                $res = urlencode("用户名不存在");
                exit(json_encode($res));
            }
        } else {
            $res = urlencode("用户名密码错误");
            exit(json_encode($res));
        }
        /*
         * 0：表示登录成功，1：表示密码错误，2：用户名不存在，3：用户名密码错误
         */
        break;

    default:
        exit(json_encode(error));
}
?>