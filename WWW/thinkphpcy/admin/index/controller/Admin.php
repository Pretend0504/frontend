<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
header('Content-type:text/html;charset="utf-8"');
class Admin extends Controller
{
	public function _initialize(){
		if(empty(session('adminloginid'))){
			$this -> redirect('index/login/adminlogin');
		}else{
			$user = Db::table('admin_user') -> where('id',session('adminloginid')) -> find();
			$this -> assign('info',$user);
		}
	}
    public function index(){
		
		return view();
	}
	 public function top(){
		
		return view();
	}
	 public function left(){
		
		return view();
	}
	 public function right(){
		
		return view();
	}
	/*
	学生信息
	*/
	public function userinfo(){
		$re = Db::table('st_user') -> select();
		$this -> assign('student',$re);
		return $this -> fetch('userinfo');
	}
	
	public function chengyu(){
		if(request()->isPost()){
			$search = trim(input('search'));
			if($search){
				$list = Db('chengyu') -> where('ChengYu','like','%'.$search.'%') -> order('ID  desc')->paginate(20);
			}else{
				$list = Db('chengyu')-> order('ID  desc')->paginate(20);
			}
			$rows = $list->toArray()['data'];
			$this -> assign('chengyu',$rows);
			$this -> assign('list',$list);
			return $this -> fetch();
		}else{
			$list = Db('chengyu')-> order('ID  desc')->paginate(20);
			$rows = $list->toArray()['data'];
			$this -> assign('chengyu',$rows);
			$this -> assign('list',$list);
			return $this -> fetch();
		}
	}
	public function cyadd(){
		if(request() -> isPost()){
			$data['ChengYu'] = input('chengyu');
			$data['PingYin'] = input('pinyin');
			$data['DianGu'] = input('jieshi');
			$data['ChuChu'] = input('chuchu');
			$data['LiZi'] = input('lizi');
			$data['SPingYin'] = input('shouzimu');
			$row = Db::table('chengyu') -> insert($data);
			if($row){
				$this -> success('添加成功','index/admin/chengyu');
			}else{
				$this -> error('添加失败');
			}
		}else{
			return view();
		}
	}
	public function cyupdate(){
		if(request() -> isPost()){
			$data['ID'] = input('id');
			$data['ChengYu'] = input('chengyu');
			$data['PingYin'] = input('pinyin');
			$data['DianGu'] = input('jieshi');
			$data['ChuChu'] = input('chuchu');
			$data['LiZi'] = input('lizi');
			$data['SPingYin'] = input('shouzimu');
			$row = Db::table('chengyu') -> update($data);
			if($row){
				$this -> success('修改成功','index/admin/chengyu');
			}else{
				$this -> error('修改失败');
			}
		}else{
			$id = $_GET['id'];
			$re = Db::table('chengyu') -> where('ID',$id) -> find();
			$this -> assign('cyxx',$re);
			return $this -> fetch();
		}
	}
	public function cydel(){
		$data['ID'] = input('id');
		$row = Db::table('chengyu') -> delete($data);
		if($row){
			$this -> redirect('index/admin/chengyu');
		}else{
			$this -> error('删除失败');
		}
	}
	
	/*
	题目操作
	*/
	public function question(){
		$list = Db('idioms_topic')-> order('id  desc')->paginate(20);
		$rows = $list->toArray()['data'];
		$this -> assign('timu',$rows);
		$this -> assign('list',$list);
		return view();
	}
	public function tmadd(){
		if(request() -> isPost()){
			$data['question'] = input('question');
			$data['answer'] = input('answer');
			$row = Db::table('idioms_topic') -> insert($data);
			if($row){
				$this -> success('添加成功','index/admin/question');
			}else{
				$this -> error('添加失败');
			}
		}else{
			return view();
		}
	}
	public function tmupdate(){
		if(request() -> isPost()){
			$data['id'] = input('id');
			$data['question'] = input('question');
			$data['answer'] = input('answer');
			$row = Db::table('idioms_topic') -> update($data);
			if($row){
				$this -> success('修改成功','index/admin/question');
			}else{
				$this -> error('修改失败');
			}
		}else{
			$id = $_GET['id'];
			$re = Db::table('idioms_topic') -> where('id',$id) -> find();
			$this -> assign('tmxx',$re);
			return $this -> fetch();
		}
	}
	public function tmdel(){
		$data['id'] = input('id');
		$row = Db::table('idioms_topic') -> delete($data);
		if($row){
			$this -> redirect('index/admin/question');
		}else{
			$this -> error('删除失败');
		}
	}

	

	public function feedback(){
		$re = Db::table('user_feedback') -> order('add_time desc') -> select();
		$this -> assign('fankui',$re);
		return view();
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
			$this -> assign('pinyin',$data);
		}else{
			$this -> assign('chengyu',NULL);
		}
		
		$this -> assign('type','');
		return view();
	}
	
	public function add_admin(){
		if(request() -> isPost()){
			$name=input('user');
			$pwd=input('password');
			if(preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$name)){
				$this -> error('用户名不要输入汉字');
				exit();
			}
			if(preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$pwd)){
				$this -> error('密码不要输入汉字');
				exit();
			}
			$re = Db::table('admin_user') -> where('user',$name) -> find();
			if($re){
				$this -> error('用户名已存在');
				exit();
			}
			$data['user'] = $name;
			$data['password'] = md5(trim($pwd));
			$row = Db::table('admin_user') -> insert($data);
			if($row){
				$this -> success('注册成功');
			}else{
				$this -> error('因网站原因注册失败，请联系我们');
			}
		}else{
			return view();
		}
	}
}
