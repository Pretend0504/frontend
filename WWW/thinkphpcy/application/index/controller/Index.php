<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
header('Content-type:text/html;charset="utf-8"');
class Index extends Controller
{
	public function _initialize(){
		if(empty(session('loginid'))){
			$this -> redirect('index/User/login');
		}else{
			$user = Db::table('st_user') -> where('id',session('loginid')) -> find();
			$this -> assign('info',$user);
		}
	}
    public function index(){
		
		return view();
	}
	public function startlearning(){
		$zimu = trim(input('zimu'));
		//随机生成成语
		if(!empty($zimu)){
			for($i=0;$i<10;$i++){//判断首字母
				$re[$i] = Db::table('chengyu') -> where('id',rand(1,13000)) -> find();

				$data[$i]['id'] = $re[$i]['ID'];
				$data[$i]['chengyu'] = $re[$i]['ChengYu'];
				
				$xzimu = trim(substr($re[$i]['SPingYin'],0,1));

				if($xzimu!=$zimu){
					$i--;
				}
			}
		}else{
			for($i=0;$i<10;$i++){
				$re[$i] = Db::table('chengyu') -> where('id',rand(1,13000)) -> find();
				$data[$i]['id'] = $re[$i]['ID'];
				$data[$i]['chengyu'] = $re[$i]['ChengYu'];
			}
		}
		
		$this -> assign('data',$data);
		$this -> assign('type','cyxx');
		return view();
	}
	
	public function cidian(){
		$re = Db::table('chengyu') -> select();
		for($i=0;$i<count($re);$i++){
			$xzimu = trim(substr($re[$i]['SPingYin'],0,1));
			if(empty($data[$xzimu]['shou']) && empty($data[$xzimu]['link'])){
				$data[$xzimu]['shou'] = strtoupper($xzimu);
				$data[$xzimu]['link'] = 'letter'.strtoupper($xzimu);
			}
			$data[$xzimu][$i] = $re[$i];

		}
		$this -> assign('chengyu',$data);
		$this -> assign('type','cycd');
		return view();
	}
	
	public function game(){
		for($i=0;$i<1;$i++){
			$id = rand(1,13000);
			$sql = "SELECT * FROM chengyu WHERE id={$id} AND LENGTH(ChengYu)<13";
			$re = Db::query($sql);
			if(!$re){
				$i--;
			}
		}
		for($i=0;$i<10;$i++){
				$re2[$i] = Db::table('chengyu') -> where('id',rand(1,13000)) -> find();
				$data[$i]['id'] = $re2[$i]['ID'];
				$data[$i]['chengyu'] = $re2[$i]['ChengYu'];
		}
		//$re = Db::table('chengyu') -> where('id',rand(1,13000)) -> find();
		$this -> assign('data',$data);
		$this -> assign('first',$re[0]['ChengYu']);
		$this -> assign('type','qwyx');
		return view();
	}
	
	public function gamek(){
		$type = input('type');
		if($type == 1){
			$id = rand(1,13000);
			for($i=0;$i<1;$i++){
				$sql = "SELECT * FROM chengyu WHERE id={$id} AND LENGTH(ChengYu)<13";
				$re = Db::query($sql);
				if(!$re){
					$i--;
				}
			}
			return $re[0]['ChengYu'];
		}else if($type == 2){
			$wt = trim(input('wt'));
			$da = trim(input('da'));
			if(!preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$da) || strlen($da)!=12){
				$data['code'] = 1;
				$data['info'] = '请输入四个字的汉语';
				return $data;
			}else{
				$nwt = substr($wt,9,3);
				$nda = substr($da,0,3);
				if($nwt != $nda){
					$data['code'] = 2;
					$data['info'] = '关键字不同';
					return $data;
				}else{
					$re = Db::table('chengyu') -> where('ChengYu',$da) -> find();
					if($re){
						$data['code'] = 4;
						$data['info'] = '接龙成功';
						$data['xcy'] = $da;
						return $data;
					}else{
						$data['code'] = 3;
						$data['info'] = '没有该成语';
						return $data;
					}
				}
				
			}
		}else if($type == 3){
			$wt = trim(input('wt'));
			$nwt = substr($wt,9,3);
			$re = Db::table('chengyu') -> where('ChengYu','like',$nwt.'%') -> select();
			// $data = array();
			// for($i=0;$i<count($re);$i++){
				// $data[$i] = $re[$i]['ChengYu'];
			// }
			return $re;
		}
		
	}
	
	public function shoucang(){
		$id = session('loginid');
		$zimu = input('zimu');
		if(empty($zimu)){
			$re = Db::table('user_collect') -> where('uid',$id) -> order('id desc') -> select();
		}else{
			$data['uid'] = $id;
			$data['initial'] = $zimu;
			$re = Db::table('user_collect') -> where($data) -> select();
		}
		
		$this -> assign('ciyu',$re);
		$this -> assign('type','wdsc');
		return view();
	}
	
	public function myreset(){
		$this -> assign('type','wdsz');
		return view();
	}
	
	/*
	成语详细解释
	*/
	public function word_detail(){
		$id = input('id');
		for($i=0;$i<10;$i++){
				$re[$i] = Db::table('chengyu') -> where('id',rand(1,13000)) -> find();
				$data[$i]['id'] = $re[$i]['ID'];
				$data[$i]['chengyu'] = $re[$i]['ChengYu'];
		}
		$da['cid'] = $id;
		$da['uid'] = session('loginid');
		$re = Db::table('user_collect') -> where($da) -> find();
		if($re){
			$exist = 1;
		}else{
			$exist = 0;
		}
		$re = Db::table('chengyu') -> where('id',$id) -> find();
		if($re){
			$this -> assign('exist',$exist);
			$this -> assign('data',$data);
			$this -> assign('type','cyxx');
			$this -> assign('chengyu',$re);
			return view();
		}else{
			$this -> error('因网络问题，请联系我们');
		}
	}
	
	/*
	收藏成语
	*/
	public function collect(){
		$re = Db::table('chengyu') -> where('ID',input('id')) -> find();
		
		$data['cid'] = input('id');
		$data['uid'] = session('loginid');
		$data['chengyu'] = $re['ChengYu'];
		$data['initial'] = substr(input('pinyin'),0,1);
		$data['add_time'] = date('Ymd',time());
		
		$row = Db::table('user_collect') -> insert($data);
		if($row){
			return true;
		}else{
			return false;
		}
	}
	
	public function collect_del(){
		$data['cid'] = input('id');
		$data['uid'] = session('loginid');

		$row = Db::table('user_collect')->where($data)->delete();
		if($row){
			return true;
		}else{
			return false;
		}
	}
	
	
	public function mhcx(){
		$re = Db::table('chengyu') -> where('ChengYu','like','%'.input('search').'%') -> select();
		if($re){
			for($i=0;$i<count($re);$i++){
				$xzimu = trim(substr($re[$i]['SPingYin'],0,1));
				if(empty($data[$xzimu]['shou']) && empty($data[$xzimu]['link'])){
					$data[$xzimu]['shou'] = strtoupper($xzimu);
					$data[$xzimu]['link'] = 'letter'.strtoupper($xzimu);
				}
				$data[$xzimu][$i] = $re[$i];

			}
			$this -> assign('chengyu',$data);
		}else{
			$this -> assign('chengyu',NULL);
		}
		
		$this -> assign('type','');
		return view();
	}
	
	
	public function question(){
		for($i=0;$i<5;$i++){
			$id = rand(1,56);
			$re = Db::table('idioms_topic') ->where('id',$id) -> find();
			if($re){
				$data[$i] = $re;
			}else{
				$i--;
			}
		}
		$this -> assign('start',$data);
		$this -> assign('type','cydt');
		$this -> assign('i',0);
		return view();
	}
	public function checkquestion(){
		$j = 0;
		for($i=0;$i<5;$i++){
			$id = input('id'.($i+1));
			$answer = trim(input('answer'.($i+1)));
			$re[$i] = Db::table('idioms_topic') -> where('id',$id) -> find();
			$re[$i]['answer2'] = $answer;
			if($re[$i]['answer'] == $answer){
				$re[$i]['pd'] = 1;
				$j++;
			}else{
				$re[$i]['pd'] = 0;
			}
		}
		$this -> assign('j',$j);
		$this -> assign('jieguo',$re);
		$this -> assign('type','cydt');
		return view();
	}
}
