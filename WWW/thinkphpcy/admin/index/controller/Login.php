<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;
class Login extends Controller
{	

	/*
	ajax登陆
	*/
	public function adminlogin(){
		if (request()->isPost()){
			$data['user'] = input('name');
			$data['password'] = md5(input('pwd'));
			
			$re = Db::table('admin_user')->where($data)->find();
			if($re){
				session::set('adminloginid',$re['id']);
				return true;
			}else{
				return false;
			}
		}else{
			return view();
		}
	}
	/*
	清session
	*/
	public function logout(){
		session('logined',null);
		session(null);
		$this -> redirect('index/login/adminlogin');
	}


	
}
