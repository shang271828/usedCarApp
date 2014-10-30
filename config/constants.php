<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|

| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./application/config/constants.php */

define("IS_DEBUG",TRUE);
define("IS_DEBUG_INPUT",FALSE);
define("IS_DEBUG_VAR",TRUE);

/*error description */
//常用
define("IS_DONE","操作已完成");
define("PARAMETER_MISSING", "缺少参数：");
define("WRONG_TYPE", "输入参数类型错误！");
define("WRONG_VALUE", "输入参数取值错误！");


//my_output

define("THROUGH_VERIFICATION","验证通过");
define("DEBUG_ENVIRONMENT","调试环境");
define("USERINFO_CORRECT","用户信息无误");
define("TIME_OUT", "连接超时");
define("ID_NONEXIST", "用户id不存在");
define("PASSWORD_INCORRECT", "密码不正确");

//getNotice API
define("NULL_NOTICE","返回信息为空！");
define("GET_NOTICE","获取信息列表：");
define("GET_NOTICE_DETAIL","获取信息详情");

//followNotice API
define("FOLLOW", "已关注");
define("FOLLOW_CANCEL", "已取消关注");

//praiseNotice API
define("PRAISE", "已点赞");
define("PRAISE_CANCEL", "已取消点赞");

//publishCarNotice API
define("PUBLISH_CAR_NOTICE","二手车信息已发布");
define("EDIT_CAR_NOTICE","二手车信息已编辑");
//publishCommentNotice API
define("PUBLISH_COMMENT_NOTICE","评论信息已发布");

//searchNotice API
define("NO_CORRESPONDING_NOTICE","未搜索到相关信息");

//uploadImage API
define("UPLOAD_IMAGE","图片已上传");

//article API
define("NULL_ARTICLE","返回文章为空");
define("GET_ARTICLE","获取文章列表：");
define("GET_ARTICLE_DETAIL","获取文章详情");
define("PUBLISH_ARTICLE", "发布文章");

//user API
define("GET_CAPTCHA","验证码已发送");
define("SEND_ERROR","验证码发送错误");
define("CAPTCHA_ERROR","验证码错误");
define("GET_FRIEND_LIST","获取好友列表");
define("GET_USERINFO","获取用户信息");
define("USER_ADD","已添加注册用户");
define("NAME_EXIST","用户名已存在");
define("PHONE_EXIST","手机号码已注册");
define("RESET_PASSWORD","重设密码");
define("SET_PREFERENCE","用户偏好已设置");

//hou
define('CMS_PATH', 'http://localhost/icms/modules/blog/');
define('CMS_BASE_URL', 'http://localhost/CI_Completed/index.php/cms/');


