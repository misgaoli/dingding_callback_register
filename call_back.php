<?php
//根据自己的业务处理

use XXX(自己的代码目录)\DingDing\DingDingSdk;

    //注册
    function  actionDdRegister()
    {
        $dingding=new DingDingSdk();

        $data=[];

        $data["call_back_tag"]=["user_add_org","user_modify_org","user_leave_org","label_user_change"];
        $data["token"]="XXX定义";//必须和CallbackTest中TOKEN保持一致
        $data["aes_key"]="XXXXXXXX";//必须和CallbackTest中ENCODING_AES_KEY保持一致
        $data["url"]="自定义";


        $res=$dingding->RegisterCallBack($data);
        var_dump($res);die;

    }

    //处理企业的钉钉回调
    
    function  CallbackTest(){
       
        define("SUITE_KEY", "XXXX");//自己的钉钉后台key
        define("TOKEN", "XXXXXXXX");//自定义
        define("ENCODING_AES_KEY", "XXXXXXX");//自定义

        $signature = $_GET["signature"];
        $timeStamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $postdata = file_get_contents("php://input");
        $postList = json_decode($postdata,true);
        $encrypt = $postList['encrypt'];
        $crypt = new DingtalkCrypt(TOKEN, ENCODING_AES_KEY, SUITE_KEY);

        //第一次注册回调地址时-----将如下注释打开
            /*
            $res = "success";
            $encryptMsg = "";

            $errCode = $crypt->EncryptMsg($res, $timeStamp, $nonce, $encryptMsg);
            Log::e("ERR:" . $errCode);
            echo $errCode['data'];die;
            */

        $msg = "";
        $res = $crypt->DecryptMsg($signature, $timeStamp, $nonce, $encrypt, $msg);

        //当解密失败记录
        if ($res['ErrorCode'] != 0){
            Log::e("ErrorCode:" . $res['ErrorCode']);
            Log::e(json_encode($_GET));
            Log::e(json_encode($encrypt));
            Log::e('------------------------------------------------------------------------------------------');
        }else{
            Log::e("SuccessCode:" . $res['ErrorCode']);
            //当返回值为空记录加密字符串信息
            if(!$res['data']){
                Log::e(json_encode($_GET));
                Log::e(json_encode($encrypt));
            }
            Log::e(json_encode($res['data']));
            Log::e('------------------------------------------------------------------------------------------');
            //创建成功后的回调推送
            $eventMsg = json_decode($res['data']);
            $eventType = $eventMsg->EventType;
            $UserId = $eventMsg->UserId;
            $dingdingSdk= new DingDingSdk();
            $dingdingSdk->DealCallBack($eventType,$UserId);
            echo "success";die;
            

        }

    }