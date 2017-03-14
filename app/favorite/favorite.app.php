<?php
/**
 * @package iCMS
 * @copyright 2007-2017, iDreamSoft
 * @license http://www.idreamsoft.com iDreamSoft
 * @author coolmoo <idreamsoft@qq.com>
 */
defined('iPHP') OR exit('What are you doing?');

class favoriteApp {
	public $methods	= array('add','delete','create','list');
    public function __construct() {
        $this->id = (int)$_GET['id'];
    }
    private function __login(){
        user::get_cookie() OR iUI::code(0,'iCMS:!login',0,'json');
    }
    public function API_list(){
        user::get_cookie() OR iUI::code(0,'iCMS:!login',0,'json');

        iPHP::app('favorite.func');
        $array = favoriteFunc::favorite_list(array('userid'=>user::$userid));
        iUI::json($array);
    }
    /**
     * [ACTION_delete 删除收藏]
     */
    public function ACTION_delete(){
        $this->__login();

        $uid     = user::$userid;
        $appid   = (int)$_POST['appid'];
        $iid     = (int)$_POST['iid'];
        $cid     = (int)$_POST['cid'];
        $suid    = (int)$_POST['suid'];
        $id      = (int)$_POST['id'];
        $fid     = (int)$_POST['fid'];
        $title   = iSecurity::escapeStr($_POST['title']);
        $url     = iSecurity::escapeStr($_POST['url']);

        if(!$fid||!$url){
            iUI::code(0,'iCMS:error',0,'json');
        }
        iDB::query("
            DELETE
            FROM `#iCMS@__favorite_data`
            WHERE `uid` = '$uid'
            AND `fid` = '$fid'
            AND `url` = '$url';
        ");
        iDB::query("
            UPDATE `#iCMS@__favorite`
            SET `count` = count-1
            WHERE `id` = '$fid' AND `count`>0;
        ");
        iUI::code(1,0,0,'json');
    }
    /**
     * [ACTION_add 添加到收藏夹]
     */
    public function ACTION_add(){
        $this->__login();

        $uid     = user::$userid;
        $appid   = (int)$_POST['appid'];
        $iid     = (int)$_POST['iid'];
        $cid     = (int)$_POST['cid'];
        $suid    = (int)$_POST['suid'];
        $id      = (int)$_POST['id'];
        $fid     = (int)$_POST['fid'];
        $title   = iSecurity::escapeStr($_POST['title']);
        $url     = iSecurity::escapeStr($_POST['url']);
        $addtime = time();

        $id  = iDB::value("SELECT `id` FROM `#iCMS@__favorite_data` WHERE `uid`='$uid' AND `fid`='$fid' AND `url`='$url' LIMIT 1");
        $id && iUI::code(0,'iCMS:favorite:failure',0,'json');

        $fields = array('uid', 'appid', 'fid', 'iid', 'url', 'title', 'addtime');
        $data   = compact ($fields);
        $fdid   = iDB::insert('favorite_data',$data);
        if($fdid){
            iDB::query("
                UPDATE `#iCMS@__favorite`
                SET `count` = count+1
                WHERE `id` = '$fid';
            ");
            iUI::code(1,'iCMS:favorite:success',$fdid,'json');
        }
        iUI::code(0,'iCMS:favorite:error',0,'json');
    }
    /**
     * [ACTION_create 创建新收藏夹]
     */
    public function ACTION_create(){
        $this->__login();

        $uid         = user::$userid;
        $nickname    = user::$nickname;
        $title       = iSecurity::escapeStr($_POST['title']);
        $description = iSecurity::escapeStr($_POST['description']);
        $mode        = (int)$_POST['mode'];

        empty($title) && iUI::code(0,'iCMS:favorite:create_empty',0,'json');
        $fwd  = iPHP::callback(array("filterApp","run"),array(&$title));
        $fwd && iUI::code(0,'iCMS:favorite:create_filter',0,'json');

        if($description){
            $fwd  = iPHP::callback(array("filterApp","run"),array(&$description));
            $fwd && iUI::code(0,'iCMS:favorite:create_filter',0,'json');
        }

        $max  = iDB::value("SELECT COUNT(id) FROM `#iCMS@__favorite` WHERE `uid`='$uid'");
        $max >=10 && iUI::code(0,'iCMS:favorite:create_max',0,'json');
        $count  = 0;
        $follow = 0;
        $fields = array('uid', 'nickname', 'title', 'description', 'follow', 'count', 'mode');
        $data   = compact ($fields);
        $cid    = iDB::insert('favorite',$data);
        $cid && iUI::code(1,'iCMS:favorite:create_success',$cid,'json');
        iUI::code(0,'iCMS:favorite:create_failure',0,'json');
    }

}
