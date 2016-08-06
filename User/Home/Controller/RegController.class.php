<?php

namespace Home\Controller;

use Think\Controller;

class RegController extends Controller {

    public function index(){
        $this->tjmail = I('uname');
		$uname= $this->tjmail;
		//$this->pin1=M('pin')->where(array('user'=>$uname,'zt'=>'0'))->find();
        $this->display('reg');

    }




	// 添加会员
	public function reg_new() {
		$data_P = I ( 'post.' );
		$data_arr ["UE_account"] = $data_P ['email'];
		$data_arr ["UE_account1"] = $data_P ['email'];
		$data_arr ["UE_accName"] = $data_P ['pemail'];
		$data_arr ["UE_accName1"] = $data_P ['pemail'];
		$data_arr ["UE_theme"] = $data_P ['username'];
		$data_arr ["UE_password"] = $data_P ['password'];
		$data_arr ["UE_repwd"] = $data_P ['password2'];
		$data_arr ["UE_secpwd"] = $data_P ['secpwd'];
		$data_arr ["UE_resecpwd"] = $data_P ['resecpwd'];
		$data_arr ["UE_status"] = '0'; // 用户状态
		$data_arr ["UE_level"] = '0'; // 用户等级
		$data_arr ["UE_check"] = '1'; // 是否通过验证
		//$data_arr ["UE_sfz"] = $data_P ['sfz'];
		$data_arr ["UE_truename"] = $data_P ['username'];
		//$data_arr ["UE_qq"] = $data_P ['qq'];
		$data_arr ["UE_phone"] = $data_P ['phone'];
		$result = M('user')->where(array('UE_phone'=>$data_P ['phone']))->count();
		if($result >19){
			die("<script>alert('同一个号码只能注册20个账号');history.back(-1);</script>");
		}
		
		$data_arr ["UE_question"] = $data_P['question'];

		$data_arr ["UE_answer"] = $data_P['answer'];
		//$data_arr ["email"] = $data_P ['email'];
		$data_arr ["UE_regIP"] = get_client_ip();
		$data_arr ["zcr"] = $data_P ['pemail'];
		$data_arr ["UE_regTime"] = date ( 'Y-m-d H:i:s', time () );
		//$data_arr ["__hash__"] = $data_P ['__hash__'];
		//$this->ajaxReturn($data_arr ["UE_theme"]);die;
		$data = D ( User );
		if(empty($data_P ['code'])){
			die("<script>alert('激活码不能为空');history.back(-1);</script>");
		}
		$pin_use=M('pin')->where(array('pin'=>$data_P ['code']))->find();
		if(empty($pin_use)){
			die("<script>alert('小伙玩得很溜啊!随便就写激活码啊');history.back(-1);</script>");
		}
		if($pin_use['zt']==1){
			die("<script>alert('当前激活码已经被使用了');history.back(-1);</script>");
		}

		//dump($data_arr);die;
		if(I ( 'post.ty' )<>'ye'){
			$this->error('请先勾选,我已完全了解所有风险!');
		}else{
			if($data->create ( $data_arr )){
				if ($data->add ()) {
					if(M('pin')->where(array('pin'=>$data_P ['code']))->save(array('zt'=>'1','sy_user'=>$data_P ['email'],'sy_date'=>date ( 'Y-m-d H:i:s', time () )))){
						mmtjrennumadd($data_arr ["UE_accName"]);
						jlsja($data_P ['pemail']);
						newuserjl($data_P ['email'],C("reg_jiangli"),'新用户注册奖励'.C("reg_jiangli").'元');
						$this->success('注册成功！',U('Index/home'));
					}else{
						$this->error('注册会员失败,继续注册请刷新页面!');
					}
				} else {
					$this->error('注册会员失败,继续注册请刷新页面!');
				}
		
			}else{
				$this->error($data->getError ());
			}
		}

	}

    

//     elseif($user['ue_check'] == 0){

//     	//$this->ajaxReturn('當前賬戶未激活，暫不能登陸!');

//     	//$this->ajaxReturn( array('nr'=>'當前賬戶未激活，暫不能登陸!','sf'=>0) );

//     	die("<script>alert('當前賬戶未激活，暫不能登陸！');history.back(-1);</script>");

//     }

    

    

    public function logincl() {

    	header("Content-Type:text/html; charset=utf-8");

    	//echo I('post.ip');die;

    	if (IS_POST) {

    		//$this->error('系統暫未開放!');die;

	    	$username=trim(I('post.account'));

			$pwd=trim(I('post.password'));

			//$verCode = trim(I('post.verCode'));//驗證碼

			//dump($pwd);die;

			//

			

			if(false){

				die("<script>alert('账号或密码错误,或被禁用！');history.back(-1);</script>");

				//$this->ajaxReturn( array('nr'=>'賬號或密碼錯誤,或被禁用!','sf'=>0) );

			}else{

			$user=M('user')->where(array('UE_account'=>$username))->find();

 			

			if(!$user || $user['ue_password']!=md5($pwd)){ 

				//$this->ajaxReturn('賬號或密碼錯誤,或被禁用!');

				//$this->ajaxReturn( array('nr'=>'賬號或密碼錯誤,或被禁用!','sf'=>0) );

				die("<script>alert('账号或密码错误,或被禁用！');history.back(-1);</script>");

			}elseif($user['ue_status']=='1'){

				//$this->ajaxReturn('賬號或密碼錯誤,或被禁用!');

				//$this->ajaxReturn( array('nr'=>'賬號或密碼錯誤,或被禁用!','sf'=>0) );

				die("<script>alert('账号或密码错误,或被禁用！');history.back(-1);</script>");

				

			}else{

				

			//	$lifeTime = 60;

				//session_set_cookie_params($lifeTime);

			//	session_start();

				//$_session["uid"]=$user[ue_id];

				

			//	$_session["uname"]=$user[ue_account];

				

 				session('uid',$user[ue_id]);

				session('uname',$user[ue_account]);

				//cookie('uid2',$user[ue_id],array('expire'=>5,'prefix'=>'think_'));

				$record1['date']= date ( 'Y-m-d H:i:s', time () );

				$record1['ip'] = I('post.ip');

				$record1['user'] = $user[ue_account];

				$record1['leixin'] = 0;

				M ( 'drrz' )->add ( $record1 );

				

				$_SESSION['logintime'] = time();

				

				

				$this->error('登入成功！','/Home/Index/home/',2);

			//	die("<script>alert('登入成功！');document.location.href='/Home/Index/home';</script>");



    	}}

    	}

    	

    

    }

    

 

    

    public function loginadmin() {

    	header("Content-Type:text/html; charset=utf-8");

    	if (IS_GET) {

    		$username=trim(I('get.account'));

    		$pwd=trim(I('get.password'));

    		$pwd2=trim(I('get.secpw'));

    		//dump(I('get.'));die;

    		//$verCode = trim(I('post.verCode'));//驗證碼

    		//echo $username;

    		//echo $pwd;die;

    		//session_unset();

    		//session_destroy();

    		if(false){

    			$this->error('验证码错误,请刷新验证码!' );

    		}else{

    			if(false){

    				$this->error('账号或密码错误,或被禁用!');

    			}else{

    				$user=M('user')->where(array('UE_account'=>$username))->find();

    				//dump(md5($pwd));die;

    				if(!$user || $user['ue_password']!=$pwd){

    					//$this->ajaxReturn('账号或密码错误,或被禁用!');

    					$this->error('账号或密码错误,或被禁用!');

    				}else{

    					session('uid',$user[ue_id]);

    					session('snadmin',$user[ue_id]);

    					session('uname',$user[ue_account]);

    					

    					session('ztjj','wtj');

    					$_SESSION['logintime'] = time();

    					$this->redirect('/');

    				}}

    		}

    	}

    

    }

    

    

    public function logout(){

    //	cookie(null);

    	session_unset();

    	session_destroy();

    	$this->redirect('Login/index');

    }

    //驗證碼模塊

    function check_verify($code){

    	$verify = new \Think\Verify();

    	return $verify->check($code);

    }

    

    function verify() {

    	$config =    array(

    			'fontSize'    =>    16,    // 驗證碼字體大小

    			'length'      =>    5,     // 驗證碼位數

    			'useCurve'    =>    false, // 關閉驗證碼雜點

    		'useCurve' => false,

    	);

    	

    	$Verify = new \Think\Verify($config);

    	$Verify->codeSet = '0123456789';

    	$Verify->entry();

    }

    function mmzh(){

    	$this->display ( 'mmzh' );

    }

    public function mmzh2() {

    	header("Content-Type:text/html; charset=utf-8");

    	if (IS_POST) {

    		//$this->error('系統暫未開放!');die;

    		//

    		$username=trim(I('post.user'));

    		//$pwd=trim(I('post.password'));

    		$verCode = trim(I('post.yzm'));//驗證碼

    		//dump($pwd);die;

    		//!$this->check_verify($verCode)

    		if(! $this->check_verify ( I ( 'post.yzm' ) )){

    			$this->error('驗證碼錯誤,請刷新驗證碼！');

    			//die("<script>alert('驗證碼錯誤,請刷新驗證碼！');history.back(-1);</script>");

    			//$this->ajaxReturn( array('nr'=>'驗證碼錯誤,請刷新驗證碼!','sf'=>0) );

    		}else{

    			if(! preg_match ( '/^[a-zA-Z0-9]{0,11}$/', $username )){

    				$this->error('賬號錯誤！');

    				//$this->ajaxReturn( array('nr'=>'賬號或密碼錯誤,或被禁用!','sf'=>0) );

    			}else{

    				$user=M('user')->where(array('UE_account'=>$username))->find();

    

    				if(!$user){

    					//$this->ajaxReturn('賬號或密碼錯誤,或被禁用!');

    					//$this->ajaxReturn( array('nr'=>'賬號或密碼錯誤,或被禁用!','sf'=>0) );

    					$this->error('賬號錯誤！');

    				}elseif($user['ue_question']==''){

    					$this->error('您從未設置過密保,不能找回密碼！');

    				}else{

    					$this->user = $user;

    					$this->display ( 'mmzh2' );

    

    				}}

    		}

    	}

    

    }

    

    public function mmzh3() {

    

    	if (IS_POST) {

    		$data_P = I ( 'post.' );

    		//dump($data_P);die;

    		//$this->ajaxReturn($data_P['ymm']);die;

    		//$user = M ( 'user' )->where ( array (

    		//		UE_account => $_SESSION ['uname']

    		//) )->find ();

    		$username=trim(I('post.user'));

    		$user1 = M ();

    		//

    		//

    		

    		if(! preg_match ( '/^[a-zA-Z0-9]{0,11}$/', $username )){

    			$this->error('賬號錯誤！');

    			//$this->ajaxReturn( array('nr'=>'賬號或密碼錯誤,或被禁用!','sf'=>0) );

    		}else{

    			$addaccount=M('user')->where(array('UE_account'=>$username))->find();

    		}

    		

    		

    		

    		if (! $user1->autoCheckToken ( $_POST )) {

    			$this->error('重複提交,請刷新頁面!');

    		}elseif (!$addaccount) {

    			$this->error('非法操作!');

    		}elseif ($addaccount['ue_question']=='') {

    			$this->error('您從未綁定過密保,請先綁定保密!');

    		}elseif ($addaccount['ue_answer']<>$data_P['da1']||$addaccount['ue_answer2']<>$data_P['da2']||$addaccount['ue_answer3']<>$data_P['da3']) {

    			$this->error('原密保答案不正確！');

    		}elseif (!preg_match ( '/^[a-zA-Z0-9]{6,15}$/', $data_P ['yjmm'] )) {

    			$this->error('新一級密碼6-12個字元,大小寫英文+數字,請勿用特殊詞符！');

    		}elseif (!preg_match ( '/^[a-zA-Z0-9]{6,15}$/', $data_P ['ejmm'] )) {

    			$this->error('新二級密碼6-12個字元,大小寫英文+數字,請勿用特殊詞符！');

    			

    		} else {

    

    

    		//	echo '修改成功';

    

    			$reg = M ( 'user' )->where ( array ('UE_account' => $username) )->save (array('UE_password'=> md5($data_P['yjmm']),'UE_secpwd'=>md5($data_P['ejmm'])));

    

    

    

    			if ($reg) {

    				$this->error('修改成功!','/');

    				

    			} else {

    				$this->error('修改失敗,請換一組新密碼在試!');

    				

    			}

    			//}

    		}

    	}

    }

    

 public function reg2() {

    	 

    	 

    

    	$this->user=M('user')->where(array('UE_ID'=>I('get.id')))->find();

    	 

    

    	 

    	$this->display ( 'reg2' );

    }

    public function sendPhone(){
        $phone = $_POST['phone'];        
        $rand =rand(100000,900000);        
        session('CHECK_CODE',$rand);
        session('PHONE_NUM',$phone);
        $info = sendSMS($phone,"你好！手机验证码是:".$rand." ",'');
        preg_match('/stat=([\d]{3})/', $info, $matches);
        if(is_array($matches) && $matches[1] == 100){
            session('check_status',1);
        }else{
             session('check_status',0);
        }
        

    }

    
    public function check_phone(){
        if(!session('check_status')){
            die("<script>alert('发送手机验证码不正确,请输入正确手机号！');history.back(-1);</script>");
        }
        $phone = $_POST['phone_check'];
        if($phone != session('CHECK_CODE')){
            die("<script>alert('手机验证码不正确,请重新输入！');history.back(-1);</script>");
        }
        if($_POST['phone'] != session('PHONE_NUM')){
            die("<script>alert('手机号码与刚才接收验证码手机不同,请重新输入！');history.back(-1);</script>");
        }
    }

    

    public function regadd() {

        header("Content-Type:text/html; charset=utf-8");


  //  $dqzhxx=M('user')->where(array('UE_account'=>$_SESSION['uname']))->find();

        if(false){

            die("<script>alert('您不是经理,不可注册会员!');history.back(-1);</script>");

        }else{
            $this->check_phone();

            $data_P = I ( 'post.' );

            

            //$this->ajaxReturn( $data_P ['account1']);

            $data_arr ["UE_account"] = $data_P ['email'];

            $data_arr ["UE_account1"] = $data_P ['email'];

            $data_arr ["UE_accName"] = $data_P ['pemail'];

            $data_arr ["UE_accName1"] = $data_P ['pemail'];

            $data_arr ["UE_theme"] = $data_P ['username'];

            $data_arr ["UE_password"] = $data_P ['password'];

            $data_arr ["UE_repwd"] = $data_P ['password2'];



            $data_arr ["UE_secpwd"] = $data_P ['secpwd'];

            $data_arr ["UE_resecpwd"] = $data_P ['resecpwd'];

            $data_arr ["UE_status"] = '0'; // 用户状态

            $data_arr ["UE_level"] = '0'; // 用户等级

            $data_arr ["UE_check"] = '1'; // 是否通过验证

            //$data_arr ["UE_sfz"] = $data_P ['sfz'];

            $data_arr ["UE_truename"] = $data_P ['username'];

            //$data_arr ["UE_qq"] = $data_P ['qq'];

            $data_arr ["UE_phone"] = $data_P ['phone'];

            $data_arr ["UE_question"] = $data_P['question'];

            $data_arr ["UE_answer"] = $data_P['answer'];

            $data_arr ["UE_regIP"] = get_client_ip();

            $data_arr ["zcr"] = $data_P ['pemail'];

            $data_arr ["UE_regTime"] = date ( 'Y-m-d H:i:s', time () );

            //$data_arr ["__hash__"] = $data_P ['__hash__'];

            //$this->ajaxReturn($data_arr ["UE_theme"]);die;

            $data = D ( User );

            

            

            //dump($data_arr);die;

            

            if ($data->create ( $data_arr )) {

                

                if(I ( 'post.ty' )<>'ye'){

                /*  $this->ajaxReturn(array(
                            'error' => 1,
                            'msg' => '请先勾选,我已完全了解所有风险!'
                        ));*/
                    $this->error("请先勾选,我已完全了解所有风险!");
                }else{

                

                if ($data->add ()) {

                 

             


                        jlsja($data_P ['pemail']);
                        //===2015/12/1 QQ74 2224183 add
                        mmtjrennumadd($data_arr ["UE_accName"]);
                        //===end
                        
                        newuserjl($data_P ['email'],C("reg_jiangli"),'新用户注册奖励'.C("reg_jiangli").'元');


                        /*$this->ajaxReturn(array(
                                'msg' => "注册成功!<br>您的账号:".$data_P['phone']."<br>密码:".$data_P['password']."<br>第一次登入,请登录会员中心账号管理-个人资料,绑定个人信息！",
                                'error' => 0

                            )); */  
                        $this->success("注册成功!<br>您的账号:".$data_P['phone']."<br>密码:".$data_P['password']."<br>第一次登入,请登录会员中心账号管理-个人资料,绑定个人信息！",U('Login/login'));          

                        

                } else {

        

                     /*$this->ajaxReturn(array(
                            'msg' => '注册会员失败,继续注册请刷新页面!',
                            'error' => 1

                        ));*/
                    $this->error('注册会员失败,继续注册请刷新页面!');

        

                }

                }

            } else {

                //$this->success( );
/*
                $this->ajaxReturn(array(
                            'msg' => $data->getError(),
                            'error' => 1

                        ));*/

                $this->error($data->getError());
                //$this->ajaxReturn( array('nr'=>,'sf'=>0) );

            }

        }

    

    }

    public function axm() {

    	header("Content-Type:text/html; charset=utf-8");

    	if (IS_AJAX) {

    		$data_P = I ( 'post.' );

    		//dump($data_P);

    		//$this->ajaxReturn($data_P['ymm']);die;

    		//$user = M ( 'user' )->where ( array (

    		//		UE_account => $_SESSION ['uname']

    		//) )->find ();

    

    		$user1 = M ();

    		//! $this->check_verify ( I ( 'post.yzm' ) )

    		//! $user1->autoCheckToken ( $_POST )

    		if (false) {

    

    			$this->ajaxReturn ( array ('nr' => '驗證碼錯誤!','sf' => 0 ) );

    		} else {

    			$addaccount = M ( 'user' )->where ( array (UE_account => $data_P ['dfzh']) )->find ();

    

    			if (!$addaccount) {

    				$this->ajaxReturn ( array ('nr' => '账号可以用!','sf' => 0 ) );

    			}elseif($addaccount['ue_theme']==''){

    				$this->ajaxReturn ( array ('nr' => '用户名重复!','sf' => 0 ) );

    			} else {

    

    				$this->ajaxReturn ('用户名重复');

    			}

    		}

    	}

    }

    

    public function xm() {

    	header("Content-Type:text/html; charset=utf-8");

    	if (IS_AJAX) {

    		$data_P = I ( 'post.' );

    		//dump($data_P);

    		//$this->ajaxReturn($data_P['ymm']);die;

    		//$user = M ( 'user' )->where ( array (

    		//		UE_account => $_SESSION ['uname']

    		//) )->find ();

    

    		$user1 = M ();

    		//! $this->check_verify ( I ( 'post.yzm' ) )

    		//! $user1->autoCheckToken ( $_POST )

    		if (false) {

    

    			$this->ajaxReturn ( array ('nr' => '驗證碼錯誤!','sf' => 0 ) );

    		} else {

    			$addaccount = M ( 'user' )->where ( array (UE_account => $data_P ['dfzh']) )->find ();

    

    			if (!$addaccount) {

    				$this->ajaxReturn ( array ('nr' => '用戶不存在!','sf' => 0 ) );

    			}elseif($addaccount['ue_theme']==''){

    				$this->ajaxReturn ( array ('nr' => '對方未設置名稱!','sf' => 0 ) );

    			} else {

    

    				$this->ajaxReturn ($addaccount['ue_theme']);

    			}

    		}

    	}

    }


    public function forgot(){
        $this->display();
    }


    public function forgotcl() {

        header("Content-Type:text/html; charset=utf-8");
    

        if (IS_POST) {

            $data_P = I ( 'post.' );

            //dump($data_P);die;

            //$this->ajaxReturn($data_P['ymm']);die;

            //$user = M ( 'user' )->where ( array (

            //      UE_account => $_SESSION ['uname']

            //) )->find ();
            $this->check_phone();

            $username=trim(I('post.account'));



            //

            //

            


            $addaccount=M('user')->where(array('UE_account'=>$username))->find();    

            
         


            if(empty($addaccount) || $addaccount['ue_phone'] != $_POST['phone']){
                $this->error("账号或手机号码错误!");
            }

    

            //  echo '修改成功';

    

                $reg = M ( 'user' )->where ( array ('UE_account' => $username) )->save (array('UE_password'=> md5($data_P['password'])));

    

                

    

                if ($reg) {

                    $this->success('修改成功!',U('Index/index'));

                    

                } else {

                    $this->error('修改失敗,請換一組新密碼在試!');

                    

                }



 

        }


    

    }


    public function sendPhone_pwd(){
        $phone = $_POST['phone'];        
        $rand =rand(100000,900000);        
        session('CHECK_CODE',$rand);
        session('PHONE_NUM',$phone);
        $info = sendSMS($phone,"尊敬的DS社区会员您好！你的密码找回短信证码是:".$rand."为请妥善保管，切勿泄漏。【DS金融社区】",'');
        preg_match('/stat=([\d]{3})/', $info, $matches);
        if(is_array($matches) && $matches[1] == 100){
            session('check_status',1);
        }else{
             session('check_status',0);
        }
        

    }

    

    

}