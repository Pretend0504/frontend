<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;
class User extends Controller
{	
	/*
	学生登录
	*/
    public function login(){
		if (request()->isPost()){
			$data['user'] = input('name');
			$data['password'] = md5(md5(input('password')));

			$re = Db::table('st_user')->where($data)->find();
			if($re){
				$da['id'] = $re['id'];
				$da['login_time'] = date('Ymd',time());
				Db::table('st_user') -> update($da);
				session::set('loginid',$re['id']);
				$this -> redirect('index/index/index');
			}else{
				$this -> error('用户名或密码错误');
			}
		}else{
			return view();
		}
	}
	/*
	ajax登陆
	*/
	// public function loginb(){
		// if (request()->isPost()){
			// $data['user'] = input('name');
			// $data['password'] = md5(md5(input('pwd')));
			
			// $re = Db::table('st_user')->where($data)->find();
			// if($re){
				// session::set('loginid',$re['id']);
				// return true;
			// }else{
				// return false;
			// }
		// }else{
			// return view();
		// }
	// }
	/*
	清session
	*/
	public function logout(){
		session('logined',null);
		session(null);
		$this -> redirect('index/user/login');
	}
	
	/*
	学生注册
	*/
	public function register(){
		if (request()->isPost()){
			$name=input('name');
			$pwd1=input('password1');
			$pwd2=input('password2');
			if(preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$name) || strlen($name) > 18 || strlen($name) <6){
				$this -> error('用户名不要输入汉字,注意用户名长短');
				exit();
			}
			if($pwd1!=$pwd2){
				$this -> error('两次密码不一致');
				exit();
			}
			if(preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$pwd1) || strlen($pwd1) > 18 || strlen($pwd1) <6){
				$this -> error('密码不要输入汉字,注意密码长短');
				exit();
			}
			$re = Db::table('st_user') -> where('user',$name) -> find();
			if($re){
				$this -> error('用户名已存在');
				exit();
			}
			$data['user'] = $name;
			$data['password'] = md5(md5(trim($pwd1)));
			$data['login_time'] = date('Ymd',time());
			$row = Db::table('st_user') -> insert($data);
			if($row){
				$this -> success('注册成功','index/user/login');
			}else{
				$this -> error('因网站原因注册失败，请联系我们');
			}
		}else{
			return view();
		}
	}
	
	public function head(){
			$dst_file = $_FILES['file']['name'];
			$ext = explode('.',$dst_file);
			$wei = array_pop($ext);
			$head = session('logined').'_'.uniqid();
			$newHead = $head.'.'.$wei;
			
			$file = request()->file('file');
			// 移动到框架应用根目录/uploads/ 目录下
			$info = $file->validate(['size'=>1*1024*1024,'ext'=>'jpg,png,gif'])->move( 'static/uploads/head',$head);
			if($info){
				$re = Db::table('st_user') -> where('id',session('loginid')) -> update(['head' => $newHead]);
				if($re){
					$this -> success('上传成功');
					exit;
				}else{
					$this -> error('上传失败');
					exit;
				}
			}else{
				// 上传失败获取错误信息
				$this -> error('上传图片失败,注意大小以及是否是图片类型');
				
			}
	}
	
	public function change_password(){
		$pwd1 = trim(input('pwd1'));
		$pwd2 = trim(input('pwd2'));
		if(preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$pwd1) || strlen($pwd1) > 18 || strlen($pwd1) <6){
			$data['code'] = 1;
			$data['info'] = '密码请输入7-17位字母、数字、符号,';
			return $data;
			exit;
		}
		if($pwd1!=$pwd2){
			$data['code'] = 2;
			$data['info'] = '两次密码不一致';
			return $data;
			exit;
		}
		
		$da['id'] = session('loginid');
		$da['password'] = md5(md5($pwd1));
		$row = Db::table('st_user') -> update($da);
		if($row){
			session('logined',null);
			session(null);
			$data['code'] = 4;
			$data['info'] = '修改成功';
			return $data;
			exit;
		}else{
			$data['code'] = 3;
			$data['info'] = '意外原因，修改失败(密码不能重复)';
			return $data;
			exit;
		}
	}
	
	public function feedback(){
		$re = Db::table('st_user') -> where('id',session('loginid')) -> find();
		$data['uid'] = session('loginid');
		$data['u_user'] = $re['user'];
		$data['name'] = input('name');
		$data['contact'] = input('contact');
		$data['content'] = input('content');
		$data['add_time'] = time();
		$row = Db::table('user_feedback') -> insert($data);
		if($row){
			$this -> success('提交成功');
		}else{
			$this -> error('提交失败');
		}
	}
	
	
}
