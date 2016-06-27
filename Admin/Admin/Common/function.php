<?php
define('TMPL_PATH','./Assets/');

function dd($data)
{
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}

//获取时间
function get_time()
{
	list($usec, $sec) = explode(" ", microtime());
	return date('Y-m-d H:i:s.'). intval($usec*1000000);
	//return ((float)$usec + (float)$sec);
}

/*
* http request tool
*/
/*
* get method
*/
function get($url, $param = array())
{
	if (!is_array($param)) {
		throw new Exception("参数必须为array");
	}
	$p = '';
	foreach ($param as $key => $value) {
		$p = $p . $key . '=' . $value . '&';
	}
	if (preg_match('/\?[\d\D]+/', $url)) {//matched ?c
		$p = '&' . $p;
	} else if (preg_match('/\?$/', $url)) {//matched ?$
		$p = $p;
	} else {
		$p = '?' . $p;
	}
	$p = preg_replace('/&$/', '', $p);
	$url = $url . $p;
	//echo $url;
	$httph = curl_init($url);
	curl_setopt($httph, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($httph, CURLOPT_SSL_VERIFYHOST, 1);
	curl_setopt($httph, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($httph, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");

	curl_setopt($httph, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($httph, CURLOPT_HEADER, 0);
	$rst = curl_exec($httph);
	curl_close($httph);
	return $rst;
}

/*
* post method
*/
function post($url, $param = array())
{
	if (!is_array($param)) {
		throw new Exception("参数必须为array");
	}
	$httph = curl_init($url);
	curl_setopt($httph, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($httph, CURLOPT_SSL_VERIFYHOST, 1);
	curl_setopt($httph, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($httph, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
	curl_setopt($httph, CURLOPT_POST, 1);//设置为POST方式
	curl_setopt($httph, CURLOPT_POSTFIELDS, $param);
	curl_setopt($httph, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($httph, CURLOPT_HEADER, 0);
	$rst = curl_exec($httph);
	curl_close($httph);
	return $rst;
}

/**
 * 获取排序后的分类
 * @param  [type]  $data  [description]
 * @param  integer $pid   [description]
 * @param  string  $html  [description]
 * @param  integer $level [description]
 * @return [type]         [description]
 */
function getSortedCategory($data,$pid=0,$html="|------",$level=0)
{
	$temp = array();
	foreach ($data as $k => $v) {
		if($v['parent_id'] == $pid){

			$str = str_repeat($html, $level);
			$v['html'] = $str;
			$temp[] = $v;

			$temp = array_merge($temp,getSortedCategory($data,$v['id'],'|------',$level+1));

		}

	}
	return $temp;
}


/**
 * 获取排序后的栏目
 * @param  [type]  $data  [description]
 * @param  integer $pid   [description]
 * @param  string  $html  [description]
 * @param  integer $level [description]
 * @return [type]         [description]
 */
function getSortedColl($data,$pid=0,$html="|------",$level=0)
{
	$temp = array();
	foreach ($data as $k => $v) {
		if($v['parent_id'] == $pid){

			$str = str_repeat($html, $level);
			$v['html'] = $str;
			$temp[] = $v;

			$temp = array_merge($temp,getSortedColl($data,$v['id'],'|------',$level+1));

		}

	}
	return $temp;
}

/**
 * 根据key，返回当前行的所有数据
 * @param  string  $key  字段key
 * @return array         当前行的所有数据
 */
function getSettingValueDataByKey($key)
{
	return M('setting')->getByKey($key);
}

/**
 * 根据key返回field字段
 * @param  string $key   [description]
 * @param  string $field [description]
 * @return string        [description]
 */
function getSettingValueFieldByKey($key,$field)
{
	return M('setting')->getFieldByKey($key,$field);
}

/**
 *
 */
function saveMemImg($img,$filename) {

	$attached_type = '';
	if(strstr($img,'data:image/jpeg;base64,')) {
		$img_base = str_replace('data:image/jpeg;base64,', '', $img);
		$attached_type = 'jpg';
	} elseif(strstr($img,'data:image/png;base64,')) {
		$img_base = str_replace('data:image/png;base64,', '', $img);
		$attached_type = 'png';
	} elseif(strstr($img,'data:image/gif;base64,')) {
		$img_base = str_replace('data:image/gif;base64,', '', $img);
		$attached_type = 'gif';
	} else {
		return $img;
	};
	if($attached_type!='') {
		$img_decode = base64_decode($img_base);

		# 数据库判断图片后缀，确定展示张数。
		# 数据库判断图片后缀，确定展示张数。

		$og = 'og/'.$filename.'.'.$attached_type;
		$r = _upload2oss($og,$img_decode);

        if($r) {
			$ogurl = C('UPLOAD_GOOD_IMG_URL') . $filename.'.'.$attached_type;
			return $ogurl;
		}
	}
			return "";
}

function _upload2oss($path,$content){
	try {
		$ossClient = new \Extend\OSS\OssClient( C('OSS_ACCESS_KEYID'), C('OSS_ACCESS_KEYSECRET'), C('OSS_ENDPOINT'), true);
	} catch (OssException $e) {
		print $e->getMessage();
	}

	$ossClient->setTimeout(3600);      // 设置请求超时时间，单位秒，默认是5184000秒, 这里建议不要设置太小，如果上传文件很大，消耗的时间会比较长
	$ossClient->setConnectTimeout(10); // 设置连接超时时间，单位秒，默认是10秒


	$bucket = C('OSS_BUCKET');
	try {
		$ossClient->createBucket($bucket,\Extend\OSS\OssClient::OSS_ACL_TYPE_PUBLIC_READ);
	} catch (OssException $e) {
		print $e->getMessage();
	}

	//$options = array(\Extend\OSS\OssClient::OSS_HEADERS => array(
	//	'x-oss-meta-self-define-title2' => 'user define meta info',
	//));

	try{ $ossClient->putObject($bucket, $path, $content); }
	catch(OssException $e) {
		printf(__FUNCTION__ . ": FAILED\n");
		printf($e->getMessage() . "\n");
		return false;
	}
	return true;
}

function imageUrl($path,$spec='sm'){
	if($path){

		switch($spec){
			case 'sm':
				return $path.'@0o_0l_80w_90q';
			case 'md':
				return $path.'0o_0l_500w_90q';
			case 'lg':
				return $path.'0o_0l_800w_90q';
			default:
				return 'default';# 默认图片

		}
	}else{
		# 默认图片地址
		echo 'default';
	}
}

//test
# 给定一组数字如 array(1,2,3)
# 从一个初始列表选择不存在的随机数
function getRandomPz($extnum='')
{
	if(empty($extnum))
		return 1;
	$defnum = array(1, 2, 3, 4, 5, 6);
	$newnum = array_diff_assoc($defnum, $extnum);
	return array_rand($newnum);
}

# 区分图片来源
function imgSource($url){

	$source_img_host = parse_url(C('BASE_GOOD_IMG_URL'))['host'];
	$upload_img_hsot = parse_url(C('UPLOAD_GOOD_IMG_URL'))['host'];
	$host = parse_url($url);
	switch($host['host']){
		case $source_img_host:
			return 1;
		case $upload_img_hsot:
			return 0;
	}
}

# 递归分类数据
function getTypeList(&$dat, $company_id, $deep){
	$goods_type = session('goods_type');
	if(!$goods_type){
		$url = C('API_TYPE_URL').'?cid='.$company_id;
		$tp = file_get_contents($url);

		$tp = json_decode($tp, true);
		$dat = $tp['data'];

		$dat = getTypeList_deep($dat, $company_id, $deep);
		session('goods_type', $dat);
	}
	else{
		$dat = $goods_type;
	}

	return $dat;
}


# 递归分类数据
function getTypeList_deep(&$dat, $company_id, $deep){

	if($deep <= 0)
		return ;

	$deep = $deep - 1;

	foreach($dat as $key=>$val){
		$url = C('API_TYPE_URL').'?cid='.$company_id.'&code='.$val['code'];
		$tp = file_get_contents($url);
		$tp = json_decode($tp, true);
		if($tp && !empty($tp['data'])){

			getTypeList_deep($tp['data'], $company_id, $deep);
			$dat[$key]['data'] = $tp['data'];
		}
	}
	return $dat;
}

// DATA : 2维数组
function write_excel($data, $title){
	$file_name = $title.'.xlsx';

	foreach($data as $key=>$val){
		$data[$key] = dict2list($val);
	}
	require_once 'Admin/Extend/PHPExcel.php';

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
		->setLastModifiedBy("Maarten Balliauw")
		->setTitle("Office 2007 XLSX Test Document")
		->setSubject("Office 2007 XLSX Test Document")
		->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		->setKeywords("office 2007 openxml php")
		->setCategory("Test result file");

	$num_hang = 1;
	$obj = $objPHPExcel->setActiveSheetIndex(0);
	foreach($data as $key=>$val){
		foreach($val as $key2=>$val2){
			$obj = $obj->setCellValueExplicit(get_chr($key2).$num_hang, $val2, PHPExcel_Cell_DataType::TYPE_STRING);
		}
		$num_hang ++;
	}

//    foreach($data[0] as $key=>$val){
//        $objPHPExcel->getActiveSheet()->getColumnDimension(get_chr($key))->setAutoSize(true);
//    }

	$objPHPExcel->getActiveSheet()->setTitle($file_name);
	//$objPHPExcel->setActiveSheetIndex(0);
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$file_name.'"');
	header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');

	exit;
}

function get_chr($num){
	$chr = 'A';
	if($num<=25){
		return chr(ord($chr)+$num);
	}
	else{
		$ans = $num/26-1;
		$yus = $num%26;
		return chr(ord($chr)+$ans).chr(ord($chr)+$yus);
	}
}

/**
 * 索引数组转列表数组
 * @param array $data 索引数组
 *
 * @return array 转换后的列表
 */
function dict2list($data){
	$new_data = [];
	foreach($data as $val){
		$new_data[] = $val;
	}
	return $new_data;
}