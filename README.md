1:下载了钉钉的官方demo，发现加密／解密方式使用的是不支持php7+版本的加密函数,例如如下: $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC); $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');

2:遇到的坑 注册三个参数解释 define("SUITE_KEY", "钉钉的CorpId值");//这儿有个坑，有时是需要填写应用的AppKey，所以如果遇到注册不成功，可以把这儿更换下 define("TOKEN", "自己定义的随时字符串"); define("ENCODING_AES_KEY", "自己定义46个字符的字符串");

3:代码实例

3.1:注册回调

  $dingding=new DingDingSdk();//钉钉的请求类，也可以使用官方，这是自己封装的
    $data=[];
    $data["call_back_tag"]=["user_add_org","user_modify_org","user_leave_org","label_user_change"];//注册事件
    $data["token"]="XXXXXXX";//第二步定义的TOKEN
    $data["aes_key"]="XXXXX";//第二步定义的ENCODING_AES_KEY
    $data["url"]="自己的回调地址";
   //注册钉钉的方法
    $res=$dingding->RegisterCallBack($data);
    var_dump($res);die;
3.2:验证回调 第一次注册的写法 define("SUITE_KEY", "第二步定义的SUITE_KEY"); define("TOKEN", "第二步定义的TOKEN"); define("ENCODING_AES_KEY", "第二步定义的ENCODING_AES_KEY");

    $signature = $_GET["signature"];
    $timeStamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];
    $postdata = file_get_contents("php://input");
    $postList = json_decode($postdata,true);
    $encrypt = $postList['encrypt'];
    $crypt = new DingtalkCrypt(TOKEN, ENCODING_AES_KEY, SUITE_KEY);

   $res = "success";
   $encryptMsg = "";

   $errCode = $crypt->EncryptMsg($res, $timeStamp, $nonce, $encryptMsg);
   Log::e("ERR:" . $errCode);
   echo $errCode['data'];

  //注册成功后，处理回调的写法，内容太多可以查看代码callback.php文件
