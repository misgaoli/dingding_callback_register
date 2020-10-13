<?php
/**
 * 钉钉 SDK API 操作类
 *
 * 钉钉操作文档：{@link https://ding-doc.dingtalk.com/doc#/serverapi2/clotub/1b3959fa}
 *
 */
namespace app\libs\DingDing;

use Yii;

class DingDingSdk {

    private $token_url="https://oapi.dingtalk.com/gettoken?appkey=%s&appsecret=%s";
    private $get_userids_url="https://oapi.dingtalk.com/user/getDeptMember?access_token=%s&deptId=%s";//获取部门的userid
    private $get_department_list_url="https://oapi.dingtalk.com/department/list?access_token=%s&fetch_child=%s&id=%s";//获取部门列表
    private $get_department_detail_url="https://oapi.dingtalk.com/department/get?access_token=%s&id=%s";//获取部门详情
    private $get_userlist_by_dep_url="https://oapi.dingtalk.com/user/simplelist?access_token=%s&department_id=%s";//获取部门用户
    private $get_userid_by_mobile_url="https://oapi.dingtalk.com/user/get_by_mobile?access_token=%s&mobile=%s";//根据手机号获取userid
    private $send_asyncsend_url="https://oapi.dingtalk.com/topapi/message/corpconversation/asyncsend_v2?access_token=%s";//发送工作通知消息
    private $get_user_info="https://oapi.dingtalk.com/user/get?access_token=%s&userid=%s";//获取用户详细
    private $register_call_back_url="https://oapi.dingtalk.com/call_back/register_call_back?access_token=%s";//注册钉钉回调地址
    private $get_parent_url="https://oapi.dingtalk.com/department/list_parent_depts?access_token=%s&userId=%s";//查询指定用户的所有上级父部门路径
    private $get_call_back="https://oapi.dingtalk.com/call_back/get_call_back?access_token=%s";//查询回调地址
    private $del_call_back="https://oapi.dingtalk.com/call_back/delete_call_back?access_token=%s";//删除回调地址

    private $curl=null;

    private $default_agentId="";//默认的钉钉应用配置


    public function __construct($default_agentId="")
    {
      $this->curl=new Curl();
      $this->default_agentId=$default_agentId;
    }


    /*
     *获取钉钉组织架构下userid列表
     * @param $department_id,获取组织架构ID的
     * @return {json}
     * @public
     * */
    public  function  getDdUserList($department_id=1){
        $token=$this->_get_qy_token($$AppKey,$AppSecret);
        $url= sprintf($this->get_userids_url,$token,$department_id);

        //要对$data数据进行处理下,根据不同的数据类型组织不同的格式

        $res=$this->https_post_json($url,"GET");
        return $res;
    }

    /*
    *获取部门用户
    * @param $department_id,获取组织架构ID的
    * @return {json}
    * @public
    * */
    public  function  getDdDepUserList($department_id=1){
        $token=$this->_get_qy_token($$AppKey,$AppSecret);
        $url= sprintf($this->get_userlist_by_dep_url,$token,$department_id);

        //要对$data数据进行处理下,根据不同的数据类型组织不同的格式

        $res=$this->https_post_json($url,"GET");
        return $res;
    }


    /*
    *获取部门用户
    * @param $department_id,获取组织架构ID的
    * @return {json}
    * @public
    * */
    public  function  getUserIdByMobile($mobile){
        $token=$this->_get_qy_token($$AppKey,$AppSecret);
        $url= sprintf($this->get_userid_by_mobile_url,$token,$mobile);
        //要对$data数据进行处理下,根据不同的数据类型组织不同的格式

        $res=$this->https_post_json($url,"GET");
        return $res;
    }


    /*
    *获取回调地址
    * @param $department_id,获取组织架构ID的
    * @return {json}
    * @public
    * */
    public  function  getCallBackUrl(){
        $token=$this->_get_qy_token($$AppKey,$AppSecret);
        $url= sprintf($this->get_call_back,$token);
        //要对$data数据进行处理下,根据不同的数据类型组织不同的格式

        $res=$this->https_post_json($url,"GET");
        return $res;
    }

    /*
    *删除回调地址
    * @param $department_id,获取组织架构ID的
    * @return {json}
    * @public
    * */
    public  function  delCallBackUrl(){
        $token=$this->_get_qy_token($$AppKey,$AppSecret);
        $url= sprintf($this->del_call_back,$token);
        //要对$data数据进行处理下,根据不同的数据类型组织不同的格式

        $res=$this->https_post_json($url,"GET");
        return $res;
    }


    /*
    *获取用户的指定用户的所有上级父部门路径
    * @param $department_id,获取组织架构ID的
    * @return {json}
    * @public
    * */
    public  function  getParentList($userid){
        $token=$this->_get_qy_token($$AppKey,$AppSecret);
        $url= sprintf($this->get_parent_url,$token,$userid);
        //要对$data数据进行处理下,根据不同的数据类型组织不同的格式

        $res=$this->https_post_json($url,"GET");
        return $res;
    }





    /*
     *获取钉钉部门组织架构
     * @param id,获取组织架构ID的
     * @return {json}
     * @public
     * */
    public function  getOyOrg($id=1,$fetch_child="true"){
        $token=$this->_get_qy_token($$AppKey,$AppSecret);
        $url= sprintf($this->get_department_list_url,$token,$fetch_child,$id);

        //要对$data数据进行处理下,根据不同的数据类型组织不同的格式
        $res=$this->https_post_json($url,"GET");
        return $res;
    }


    /*
    *获取钉钉部门详情
    * @param id,获取组织架构ID的
    * @return {json}
    * @public
    * */
    public function  getOyOrgDetail($id=1){
        $token=$this->_get_qy_token($$AppKey,$AppSecret);
        $url= sprintf($this->get_department_detail_url,$token,$id);

        //要对$data数据进行处理下,根据不同的数据类型组织不同的格式

        $res=$this->https_post_json($url,"GET");
        return $res;
    }


    /*
     *获取钉钉里员工详细信息
     * @param id,获取组织架构ID的
     * @return {json}
     * @public
     * */
    public function  getUserInfo($userid){
        $token=$this->_get_qy_token($$AppKey,$AppSecret);
        $url= sprintf($this->get_user_info,$token,$userid);

        //要对$data数据进行处理下,根据不同的数据类型组织不同的格式

        $res=$this->https_post_json($url,"GET");
        return $res;
    }


    //给企业微信群发送消息
    //$token
    //$data 字段说明
    // chatid	:群聊id
    // msgtype :text->文本,image->图片,voice->语音,video->视频,file->文件
    // content :	消息内容，最长不超过2048个字节
    // safe	:	表示是否是保密消息，0表示否，1表示是，默认0

    public function  sendMsgUserId($data){
        $token=$this->_get_qy_token($$AppKey,$AppSecret);
        $url= sprintf($this->send_asyncsend_url,$token);
        $data["agent_id"]=Yii::$app->params["dingding_info"][$this->default_agentId]["AgentId"];
        //要对$data数据进行处理下,根据不同的数据类型组织不同的格式
        $res=$this->https_post_json($url,json_encode($data));
        return $res;
    }



    //钉钉注册回调地址
    //$token
    //$data 字段说明
    // chatid	:群聊id
    // msgtype :text->文本,image->图片,voice->语音,video->视频,file->文件
    // content :	消息内容，最长不超过2048个字节
    // safe	:	表示是否是保密消息，0表示否，1表示是，默认0

    public function  RegisterCallBack($data){
        $token=$this->_get_qy_token($$AppKey,$AppSecret);
        $url= sprintf($this->register_call_back_url,$token);
        //要对$data数据进行处理下,根据不同的数据类型组织不同的格式
        $res=$this->curl->https_post_json($url,json_encode($data));
        return $res;
    }

    //处理钉钉的回调情况
    public function  DealCallBack($eventType,$userid){
        //修改逻辑
        if($eventType=="user_modify_org"){
           

        }
        //新增逻辑
        if($eventType=="user_add_org"){
            
        }

        //删除逻辑
        if($eventType=="user_leave_org"){
            
        }
        return true;
    }


    //获取token
    //钉钉应用的id：$AppKey
    //应用的凭证：$AppSecret
    //return :json
    private  function  _get_qy_token($AppKey,$AppSecret){

        $token_key=$AppKey;

        $token=Yii::$app->redis->get($token_key);
        if($token){
            return $token;
        }else{
            $url= sprintf($this->token_url,$AppKey,$AppSecret);
            $res=$this->https_post_json($url,"GET");
            if($res["errcode"] == 0 && $res["errmsg"]=="ok"){
                //设置缓存
                Yii::$app->redis->set($token_key,$res["access_token"],$res["expires_in"]-900);
                return $res["access_token"];
            }else{
                return $res["errmsg"];
            }
        }
    }

     public  function https_post_json($url,$data){
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//设置header
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//要求结果为字符串且输出到屏幕上
        if(!empty($data)){
            curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        $return = curl_exec($ch);//运行curl
        curl_close($ch);
        return $return;//输出结果
    }

}
?>