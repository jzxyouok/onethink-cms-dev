<?php

namespace Addons\Comment;
use Common\Controller\Addon;

/**
 * 评论插件
 * @author leoding86@msn.com
 */

class CommentAddon extends Addon{

  public $info = array(
    'name'      => 'Comment',
    'title'     => '评论',
    'description'   => '本地独立评论插件',
    'status'    => 1,
    'author'    => 'leoding86@msn.com',
    'version'     => '0.8.0630Beta'
  );	
  
  public $addon_install_info=array(
				
  'install_sql'=>"DROP TABLE IF EXISTS `onethink_comment`;
	CREATE TABLE IF NOT EXISTS `onethink_comment` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`pid` INT NOT NULL DEFAULT 0,
	`pids` TEXT NOT NULL,
	`did` INT NOT NULL DEFAULT 0,
	`uid` INT NOT NULL DEFAULT 0,
	`username` VARCHAR(100) NOT NULL DEFAULT '',
	`content` TEXT NOT NULL,
	`status` INT NOT NULL DEFAULT 0,
	`create_time` INT(10) NOT NULL DEFAULT 0000000000,
	`update_time` INT(10) NULL DEFAULT 0000000000,
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ",
	
	'uninstall_sql'=>"DROP TABLE IF EXISTS `onethink_comment`;",
  );

  public $admin_list = array(
    'model'   => "Comment",   //要查的表
    'fields'  => '*',               //要查的字段
    'map'     => '',                //查询条件, 如果需要可以再插件类的构造方法里动态重置这个属性
    'order'   => 'id desc',         //排序,
    'list_grid' => array(           //这里定义的是除了id序号外的表格里字段显示的表头名和模型一样支持函数和链接
      'username:用户名',
      'content:内容',
      'did_text:评论文档',
      'create_time|time_format:评论时间',
      'status_text:状态',
      'id:操作:[EDIT]|编辑,[DELETE]|删除'
    ),
  );

  public $custom_adminlist = 'adminlist.html' ;

  public function install(){
	  
	  return $this->installAddon($this->addon_install_info);
	  
  }
  
  public function uninstall(){
	  
	  return $this->uninstallAddon($this->addon_install_info);
	  
  }

  //实现的documentDetailAfter钩子方法
  public function documentDetailAfter($param){
	  
    $_data = array('nickname'=>'','form_hidden_field'=>array('did' => '','pid' =>''));
	
    $addon_config = $this->getConfig();
	
    $Comment = D('Addons://Comment/Comment');
	
    if (!$addon_config['comment_pagesize']>0) return ;
      
	$_data= $Comment->getComments($param['id'], $addon_config['comment_pagesize'],$addon_config['comment_show_unexamine']);
	
	$_data['uid']=get_uid();
    
	$_data['nickname']=get_nickname();
	
	$_data['form_hidden_field']['did'] = $param['id'];
	
    $_data['form_hidden_field']['pid'] = 0;
	
    $this->assign('_data',$_data);

    $this->assign('_config', $addon_config);

    $this->assign('comment_title', $addon_config['comment_title']);
    
    $theme_name=$addon_config['comment_template']||'default';// 获取模版名称
    
    $template_path =$this->addon_path.'/View/'.$theme_name.'/';// 模版路径
	
	if (!file_exists($template_path)) $theme_name = 'default';
	  
    $this->display('View/'.$theme_name.'/comment');

  }

  // 加载插件Css样式
  public function pageHeader() {
    echo '<link href="'.substr($this->addon_path,1).'Public/comments.css" rel="stylesheet">';
  }

  // 加载插件js
  public function pageFooter() {
    echo '<script type="text/javascript" src="'.substr($this->addon_path,1).'Public/comments.js"></script>';
  }

}