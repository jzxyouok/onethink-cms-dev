<?php
/**
 * @Description: 投票插件
 * @author Microrain  xinjy@qq.com
 * @date 14-3-9
 * @version V0.1
 */
namespace Addons\Vote\Model;

use Think\Model;

/**
 * Vote模型
 */
class VoteModel extends Model
{
	
	public $model = array(
		'title' => '投票', //新增[title]、编辑[title]、删除[title]的提示
		'template_add' => 'View/Default/edit.html', //自定义新增模板自定义html edit.html 会读取插件根目录的模板
		'template_edit' =>'View/Default/edit.html', //自定义编辑模板html
		'search_key' => '', // 搜索的字段名，默认是title
		'extend' => 1,
	);

	public $_fields = array(
		'id' => array(
			'name' => 'id', //字段名
			'title' => 'ID', //显示标题
			'type' => 'num', //字段类型
			'remark' => '', // 备注，相当于配置里的tip
            'is_show'=>3,// 1-始终显示 2-新增显示 3-编辑显示 0-不显示
			'value' => 0, //默认值
		),
		'title' => array(
			'name' => 'title',
			'title' => '标题',
			'type' => 'string',
			'remark' => '',
			'is_show' => 1,
			'value' => 0,
			'is_must' => 1,
		),
		'description' => array(
			'name' => 'description',
			'title' => '说明',
			'type' => 'text',
			'remark' => '',
			'is_show' => 1,
			'value' => 0,
			'is_must' => 1,
		),
		'voteconfig' => array(
			'name' => 'voteconfig',
			'title' => '类型',
			'type' => 'string',
			'remark' => '',
			'is_show' => 1,
			'value' => 1,
		),
	);
	
	protected function _after_find(&$result,$options) {
		
	}
	
	protected function _after_select(&$result,$options){
		foreach($result as &$record){
			$this->_after_find($record,$options);
		}
		
		int_to_string($result,array('voteconfig'=>array(1=>"单选",2=>"多选")));
		
	}

}
