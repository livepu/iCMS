<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
*
* @author coolmoo <idreamsoft@qq.com>
* @site http://www.idreamsoft.com
* @licence http://www.idreamsoft.com/license.php
* @version 6.0.0
*/
iPHP::app('user.class','static');
iPHP::app('apps.class','static');
class commentAdmincp{
    public function __construct($appid=null) {
        $this->appid = iCMS_APP_COMMENT;
        $appid && $this->appid = $appid;
        $this->id    = (int)$_GET['id'];
    }
    public function do_config(){
        $setting = admincp::app('setting');
        $setting->app($this->appid);
    }
    public function do_save_config(){
        $setting = admincp::app('setting');
        $setting->save($this->appid);
    }
    public function do_iCMS($appid=0){
        $this->categoryApp = iPHP::app('category.admincp',$appid);
        $this->category    = $this->categoryApp->category;

        $sql = "WHERE 1=1";
		if($appid ||$_GET['appid']){
			$_GET['appid'] && $appid=(int)$_GET['appid'];
			$sql.= " AND `appid`='$appid'";
		}
        $_GET['iid']          && $sql.= " AND `iid`='".(int)$_GET['iid']."'";
        isset($_GET['status']) && $sql.= " AND `status`='".$_GET['status']."'";
		if($_GET['cid']){
            $cid = (int)$_GET['cid'];
            if(isset($_GET['sub'])){
                $cids  = iPHP::app("category")->get_ids($cid,true);
                array_push ($cids,$cid);
                $sql.=" AND cid IN(".implode(',', $cids).")";
            }else{
                $sql.=" AND cid ='$cid'";
            }
        }
		$_GET['userid']&& $sql.= " AND `userid`='".(int)$_GET['userid']."'";
		$_GET['ip']    && $sql.= " AND `ip`='".$_GET['ip']."'";
        if($_GET['keywords']) {
            $sql.="  AND CONCAT(username,title) REGEXP '{$_GET['keywords']}'";
        }

        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;
        $total		= iPHP::total(false,"SELECT count(*) FROM `#iCMS@__comment` {$sql}","G");
        iUI::pagenav($total,$maxperpage,"条评论");
        $rs     = iDB::all("SELECT * FROM `#iCMS@__comment` {$sql} order by id DESC LIMIT ".iUI::$offset." , {$maxperpage}");
        $_count = count($rs);
    	include admincp::view("comment.manage");
    }
    // function do_article(){
    // 	$this->do_iCMS(iCMS_APP_ARTICLE);
    // }
    public function do_manage($appid=0){
    	$this->do_iCMS($appid);
    }
    public function do_get_reply(){
    	$this->id OR exit("请选择要操作的评论");
        $comment = iDB::row("SELECT * FROM `#iCMS@__comment` WHERE `id`='$this->id' LIMIT 1");
        empty($comment) && exit('<div class="claerfix mb10"></div>评论已被删除');
        echo nl2br($comment->content);
        echo '<div class="claerfix mb10"></div>';
        echo '<span class="label">'.get_date($comment->addtime,'Y-m-d H:i:s').'</span> ';
        echo '<span class="label label-info"><i class="fa fa-thumbs-o-up"></i> '.$comment->up.'</span>';

    }
    public function do_del($id = null,$dialog=true){
    	$id===null && $id=$this->id;
    	$id OR iUI::alert('请选择要删除的评论!');
    	$comment = iDB::row("SELECT * FROM `#iCMS@__comment` WHERE `id`='$id' LIMIT 1");

        $table = APPS::get_table($comment->appid);

        iDB::query("UPDATE {$table['name']} SET comments = comments-1 WHERE `comments`>0 AND `{$table['primary']}`='{$comment->iid}' LIMIT 1;");
        iDB::query("UPDATE `#iCMS@__user` SET comments = comments-1 WHERE `comments`>0 AND `uid`='{$comment->userid}' LIMIT 1;");
		iDB::query("DELETE FROM `#iCMS@__comment` WHERE `id` = '$id';");

        $dialog && iUI::success('评论删除完成','js:parent.$("#id-'.$id.'").remove();');
    }
    public function do_batch(){
        $idArray = (array)$_POST['id'];
        $idArray OR iUI::alert("请选择要操作的评论");
        $ids     = implode(',',$idArray);
        $batch   = $_POST['batch'];
    	switch($batch){
    		case 'dels':
				iPHP::$break	= false;
	    		foreach($idArray AS $id){
	    			$this->do_del($id,false);
	    		}
	    		iPHP::$break	= true;
				iUI::success('评论全部删除完成!','js:1');
    		break;
		}
	}
    public function do_update(){
        if($this->id){
            $data = admincp::fields($_GET['iDT']);
            $data && iDB::update("comment",$data,array('id'=>$this->id));
            iUI::success('操作成功!','js:1');
        }
    }
}