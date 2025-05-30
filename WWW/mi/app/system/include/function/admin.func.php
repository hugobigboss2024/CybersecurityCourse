<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 

defined('IN_MET') or exit('No permission');

/**
 * 页面跳转
 */
function turnover($url, $text = '') {
	global $_M;	
	if(!$text)$text = $_M['word']['jsok'];
	if($text == 'No prompt') {
		$text = '';
	}
	$text = urlencode($text);
	echo("<script type='text/javascript'>location.href='{$url}&turnovertext={$text}';</script>");
	exit;
}

/**
 * 汉字转unicode
 */
function unicode_encode($name) {
    $name = iconv('UTF-8', 'UCS-2', $name);
    $len = strlen($name);
    $str = '';
    for ($i = 0; $i < $len - 1; $i = $i + 2)
    {
        $c = $name[$i];
        $c2 = $name[$i + 1];
        if (ord($c) > 0)
        {    // 两个字节的文字
            $str .= '\u'.base_convert(ord($c), 10, 16).base_convert(ord($c2), 10, 16);
        }
        else
        {
            $str .= $c2;
        }
    }
    return $str;
}

/**
 * 获取当前管理员信息
 * @return array  $user 返回记录当前管理员信息和有权限操作的栏目的数组
 */
function admin_information(){
	global $_M;
	met_cooike_start();
	$met_admin_table = $_M['table']['admin_table'];
	$met_column = $_M['table']['column'];
	$metinfo_admin_name = get_met_cookie('metinfo_admin_name');
	$query = "SELECT * from {$_M['table']['admin_table']} WHERE admin_id = '{$metinfo_admin_name}'";
	$user = DB::get_one($query);
	$query = "SELECT id,name from {$_M['table']['column']} WHERE access <= '{$user['usertype']}' AND lang = '{$_M['lang']}'";
	$column = DB::get_all($query);
	$user['column'] = $column;
	return $user;
}

/**
 * 获取当前管理员的权限
 * @return array  $privilege 返回记录当前管理员管理功能权限的数组（metinfo--后台所有功能管理权限;s开头--系统功能;c开头--为内容管理中，前台栏目管理权限;a开头--应用管理权限）
 */
function background_privilege(){
	global $_M;
	$metinfo_admin_name = $_M['user']['admin_name'];
	$query = "SELECT * from {$_M['table']['admin_table']} WHERE admin_id = '{$metinfo_admin_name}'";
	$user = DB::get_one($query);
	$privilege = array();
	$privilege['admin_op'] = $user['admin_op'];
	if(strstr($user['langok'], "metinfo")) {
		$privilege['langok'] = $_M['langlist']['web'];
	} else {
		$langok = explode('-',$user['langok']);
		foreach($langok as $key=>$val){
			if($val) {
				$privilege['langok'][$val] = $_M['langlist']['web'][$val];
			}
		}
	}
	if(strstr($user['admin_type'], "metinfo")){
		$privilege['navigation'] = "metinfo";
		$privilege['column'] = "metinfo";
		$privilege['application'] = "metinfo";
		$privilege['see'] = "metinfo";
	}else{
		$allidlist = explode('-', $user['admin_type']);
		foreach($allidlist as $key=>$val){
			if(strstr($val, "s")){
				$privilege['navigation'].= str_replace('s','',$val)."|";
			}
			if(strstr($val, "c")){
				$privilege['column'].= str_replace('c','',$val)."|";
			}
			if(strstr($val, "a")){
				$privilege['application'].= str_replace('a','',$val)."|";
			}
			if($val == 9999){
				$privilege['see'] = "metinfo";
			}
		}	
		$privilege['navigation'] = trim($privilege['navigation'], '|');
		$privilege['column'] = trim($privilege['column'], '|');
		$privilege['application'] = trim($privilege['application'], '|');
	}
	return $privilege;
}

/**
 * 获取当前管理员有权限操作的栏目信息
 * @return array  $column 返回记录当前管理员有权限操作的栏目信息的数组
 */
function operation_column() {
	global $_M;
	$jurisdiction = background_privilege();
	if($jurisdiction['column'] == "metinfo"){
		$query = "SELECT * from {$_M['table']['column']} WHERE lang = '{$_M['lang']}' AND module < 100";
		$admin_column = DB::get_all($query);
	}else{
		$column_id = explode('|', $jurisdiction['column']);
		$i = 0;
		foreach($column_id as $key=>$val){
			if($val){
				if($i==0){
					$sql_id = "AND (id = '{$val}' ";
				}else{
					$sql_id.= "OR id = '{$val}' ";
				}
			}
			$i++;
		}
		$sql_id.= ")";
		$query = "SELECT * from {$_M['table']['column']} WHERE lang = '{$_M['lang']}'{$sql_id} AND module < 100";
		$admin_column = DB::get_all($query);
	}
	foreach($admin_column as $key=>$val){
		$column[$val['id']] = $admin_column[$key];
	}
	return $column;
}

/**
 * 对当前管理员有权限操作的栏目信息进行整理；
 * @param  int    $type		1；按模块生成;2：按栏目生成
 * @return array  $column	返回把记录当前管理员有权限操作的栏目信息的数组按模块归类或栏目归类整理后的数组
 */
function column_sorting($type) {
	global $_M;
	$information = operation_column();
	if($type == 1){
		foreach($information as $key=>$val){
			if($val['releclass'] != 0){
				$sorting[$val['module']]['class1'][$key] = $information[$key];
				$column_classtype[] = $val['id'];
			}else{
				if($val['classtype'] == 1){
					$sorting[$val['module']]['class1'][$key] = $information[$key];
				}
				if($val['classtype'] == 2){
					$sorting[$val['module']]['class2'][$key] = $information[$key];
				}
			}
		}
		foreach($information as $key=>$val){
			$i = 0;
			if($val['classtype'] == 3){
				foreach($column_classtype as $key1=>$val1){
					if($val['bigclass'] == $val1){
						$i = 1;
					}
				}
				if($i == 1){
					$sorting[$val['module']]['class2'][$key] = $information[$key];
				}else{
					$sorting[$val['module']]['class3'][$key] = $information[$key];
				}
			}
		}
	}else{
		foreach($information as $key=>$val){
			if($val['classtype'] == 1){
				$sorting['class1'][$key] = $information[$key];
			}
			if($val['classtype'] == 2){
				$sorting['class2'][$val['bigclass']][$key] = $information[$key];
			}
			if($val['classtype'] == 3){
				$sorting['class3'][$val['bigclass']][$key] = $information[$key];
			}
		}
	}
	return $sorting;
}

/**
 * 获取后台导航栏目数组
 * @return array 返回记录后台导航栏目信息的数组
 */
function get_adminnav() {
	global $_M;
	$jurisdiction = background_privilege();
	$query = "select * from {$_M['table']['admin_column']} order by type desc,list_order";
	$sidebarcolumn = DB::get_all($query);
	$bigclass = array();
	foreach ($sidebarcolumn as $key => $val) {
		if($val['id'] == 68)$val['field'] = '1301';
		if(!is_strinclude($jurisdiction['navigation'], $val['field']) && $jurisdiction['navigation'] != 'metinfo' && $val['field']!=0)continue;
		//需要清理，下面的代码，有些栏目已经多余
		if ((($val['name'] == 'lang_indexcode') || ($val['name'] == 'lang_indexebook') || ($val['name'] == 'lang_indexbbs') || ($val['name'] == 'lang_indexskinset') ) && $_M['config']['met_agents_type'] > 1) continue;
		if ((($val['name'] == 'lang_webnanny') || ($val['name'] == 'lang_smsfuc')) && $_M['config']['met_agents_sms'] == 0) continue;
		if (($val['name'] == 'lang_dlapptips2') && $_M['config']['met_agents_app'] == 0) continue;
		//
		$val['name'] = get_word($val['name']);
		$val['info'] = get_word($val['info']);
		$bigclass[$val['bigclass']] = 1;
		switch ($val['type']) {
			case 1:
				if($bigclass[$val['id']] == 1)$adminnav[$val['id']] = $val;
			break;
			case 2:
				if (strstr($val['url'],"?")) {
					$val['url'] .= '&anyid='.$val['id'].'&lang='.$_M['lang'];
				}else{
					$val['url'] .= '?anyid='.$val['id'].'&lang='.$_M['lang'];
				}
				$val['url'] = $_M['url']['site_admin'].$val['url'];
				$adminnav[$val['id']] = $val;
			break;
		}
	}
	return $adminnav;
}

/**
 * 获取模块编号
 * @param  int    $module 模块编号
 * @return string 返回记录后台导航栏目信息的数组
 */
function modname($module) {
	global $_M;
	$metmodname = $module;
	switch ($module) {
		case 1:
			$metmodname = $_M['word']['mod1'];
			break;
		case 2:
			$metmodname = $_M['word']['mod2'];
			break;
		case 3:
			$metmodname = $_M['word']['mod3'];
		    break;
		case 4:
			$metmodname = $_M['word']['mod4'];
		    break;
		case 5:
			$metmodname = $_M['word']['mod5'];
		    break;
		case 6:
			$metmodname = $_M['word']['mod6'];
		    break;
		case 7:
			$metmodname = $_M['word']['mod7'];
		    break;
		case 8:
			$metmodname = $_M['word']['mod8'];
		    break;
		case 9:
			$metmodname = $_M['word']['mod9'];
		    break;
		case 10:
			$metmodname = $_M['word']['mod10'];
		    break;
		case 11:
			$metmodname = $_M['word']['mod11'];
		    break;
		case 12:
			$metmodname = $_M['word']['mod12'];
		    break;
		case 999:
			$metmodname = $_M['word']['modout'];
		    break;
		case 100:
			$metmodname = $_M['word']['mod100'];
		    break;
		case 101:
			$metmodname = $_M['word']['mod101'];
		    break;
		default :
			$query = "SELECT * FROM {$_M['table']['applist']}";
			$app = DB::get_all($query);
			foreach ($app as $key => $val) {
				if($module == $val['no'])$metmodname=get_word($val['appname']);
			}
		break;
	}
	return $metmodname;
}

/**
 * 获取应用列表
 */
function get_applist() {
	global $_M;
	$query = "select * from {$_M['table']['applist']} order by no";
	$result = DB::query($query);
	while ($list = DB::fetch_array($result)) {
		$list['url'] = "{$_M['url']['site_admin']}index.php?anyid={$_M['form']['anyid']}&lang={$_M['form']['lang']}&n={$list['m_name']}&c={$list['m_class']}&a={$list['m_action']}";
		$applist[$list['id']] = $list;
	}
	return $applist;
}

/**
 * 向met_tablename中插入表名
 * @param string $tablename 表名称 
 */
function add_table($tablenames) {
	global $_M;
	$list = explode('|', $tablenames);
	foreach($list as $key=>$val){
		$tablename = $val;
		if (strpos("|{$_M['config']['met_tablename']}|", "|{$tablename}|") === false) {
			$_M['config']['met_tablename'] = "{$_M['config']['met_tablename']}|{$tablename}";
			$query = "UPDATE {$_M['table']['config']} SET value = '{$_M['config']['met_tablename']}' WHERE name='met_tablename'";
			DB::query($query);
			$_M['table'][$tablename] = $_M['config']['tablepre'].$tablename;
		}
	}
}
 
/**
 * 删除met_tablename中的表名
 * @param string $tablename 表名称 
 */
function del_table($tablenames) {
	global $_M;
	$list = explode('|', $tablenames);
	foreach($list as $key=>$val){
		$tablename = $val;
		if (strpos("|{$_M['config']['met_tablename']}|", "|{$tablename}|") !== false) {
			$_M['config']['met_tablename'] = trim(str_replace("|{$tablename}|", '|', "|{$_M['config']['met_tablename']}|"), '|');
			$query = "UPDATE {$_M['table']['config']} SET value = '{$_M['config']['met_tablename']}' WHERE name='met_tablename'";
			DB::query($query);
			unset($_M['table'][$tablename]);
		}
	}
}

/**
 * 保存config表配置
 * @param array $config 需要保存的配置的Name数组
 * @param array $config 需要保存的配置的value数组，键值为Name
 * @param array $config 需要保存的配置的语言
 */
function configsave($config, $have = '', $lang = ''){
	global $_M;
	if($lang == '')$lang = $_M['lang'];
	if($have == '')$have = $_M['form'];
	$c = copykey($have, $config);
	foreach($c as $key=>$val){
		$value = mysqlcheck($have[$key]);
		if($key == 'flash_10001' && $have['mobile']=='1') {
			if(isset($_M['config'][$key])&&$value!=$_M['config'][$key]&&(isset($have[$key])or(isset($have[$key]) && !$have[$key]))){
				$query = "update {$_M[table][config]} SET mobile_value = '{$value}' WHERE name = '{$key}' and (lang='{$_M[lang]}' or lang='metinfo')";
				DB::query($query);
			}
		} else{
			if(isset($_M['config'][$key])&&$value!=$_M['config'][$key]&&(isset($have[$key])or(isset($have[$key]) && !$have[$key]))){
				$query = "update {$_M[table][config]} SET value = '{$value}' WHERE name = '{$key}' and (lang='{$_M[lang]}' or lang='metinfo')";
				DB::query($query);
			}
		}
	}
}

/**
 * 保存config表配置
 * @param string $config Name数组
 */
function mysqlcheck($str){
	global $_M;
	$str = stripslashes($str);
	$str = str_replace("'","''",$str);
	$str = str_replace("\\","\\\\",$str);
	return $str;
}

/**
 * 重新生成robots.txt
 * @param string $sitemaptype sitemap 地图后缀 xml/txt
 */
function sitemap_robots($sitemaptype=0){
	global $_M;
	$suffix = $sitemaptype;
	$met_weburl_de = DB::get_one("select * from {$_M['table']['config']} where name='met_weburl' and lang='{$_M[config][met_index_type]}'");
	$met_weburl_de = $met_weburl_de['value'];
	$robots=file_get_contents(PATH_WEB.'robots.txt');
	if($suffix){
		if(stripos($robots,'Sitemap: ')===false){
			$robots.="\nSitemap: {$met_weburl_de}sitemap.{$suffix}";
		}else{
			$robots=preg_replace('/Sitemap:.*/',"Sitemap: {$met_weburl_de}sitemap.{$suffix}",$robots);
		}
	}else{
		$robots=preg_replace("/Sitemap:.*/","",$robots);
	}
	$robots=str_replace("\n\n","\n",$robots);
	file_put_contents(PATH_WEB.'robots.txt',$robots);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>