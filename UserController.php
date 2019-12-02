 <?php
class UserController extends BaseController
{
    protected $layout = "layouts.adminlogin";
    public function __construct()
    {
        $this->beforeFilter('csrf', array(
            'on' => 'post'
        ));
        $authAllowAction = array(
            'getLogout',
            'getUserlistingajax',
            'getProfile',
            'postChangepassword',
            'getDashboard'
        );
    }
    /**
     * Used for registering user
     * 
     * @param void
     * @return void
     */
    
    public function getRegister()
    {
        if (isset(Auth::user()->id) && Auth::user()->id != 0) {
            return Redirect::to('censors/dashboard')->with('message', 'You are now logged in!');
        } else {
            $this->layout->content = View::make('user.register');
        }
    }
    public function getLogin()
    {
        $this->layout->content = View::make('user.login');
    }
    public function postUsersignin()
    {
        $userObj = DB::table('users')->where('users.email', '=', Input::get('email_login'))->select('users.is_active', 'users.is_reset_req', 'users.is_mail_req', 'users.expired_date', 'users.id')->first();
        if (is_object($userObj)) {
            if (isset($userObj->is_active) && $userObj->is_active == 'Y') {
                if (isset($userObj->is_reset_req) && $userObj->is_reset_req == 'N') {
                    if (isset($userObj->is_mail_req) && $userObj->is_mail_req == 'N') {
                        if (Auth::attempt(array(
                            'email' => Input::get('email_login'),
                            'password' => Input::get('password_login')
                        ))) {
                            return Redirect::to('censor/dashboard')->with('message', 'You are now logged in!');
                            
                        } else {
                            return Redirect::to('user/userregister')->with('error', 'username/password combination was incorrect')->withInput();
                        }
                    } else {
                        return Redirect::to('user/userregister')->with('error', 'Your Email Confirmation not Yet. Please Check your mail')->withInput();
                    }
                } else {
                    return Redirect::to('user/userregister')->with('error', 'Your have reset your password.Check your mail for password reset link.')->withInput();
                }
            } else if (isset($userObj->is_active) && $userObj->is_active == 'P') {
                return Redirect::to('user/userregister')->with('error', 'Your account is Pending.')->withInput();
            } else {
                return Redirect::to('user/userregister')->with('error', 'Your account is inactive.')->withInput();
            }
        } else {
            return Redirect::to('user/userregister')->with('error', 'Invalid login.')->withInput();
        }
    }
    public function postSignin()
    {
        $userObj = DB::table('users')->where('users.email', '=', Input::get('email'))->select('users.is_active', 'users.is_reset_req', 'users.expired_date', 'users.id')->first();
        if (is_object($userObj)) {
            if (isset($userObj->is_active) && $userObj->is_active == 'Y') {
                if (isset($userObj->is_reset_req) && $userObj->is_reset_req == 'N') {
                    if (Auth::attempt(array(
                        'email' => Input::get('email'),
                        'password' => Input::get('password')
                    ))) {
                        return Redirect::to('censor/dashboard')->with('message', 'You are now logged in!');
                        
                    } else {
                        return Redirect::to('user/login')->with('error', 'username/password combination was incorrect')->withInput();
                    }
                } else {
                    return Redirect::to('user/login')->with('error', 'Your have reset your password.Check your mail for password reset link.')->withInput();
                }
            } else if (isset($userObj->is_active) && $userObj->is_active == 'P') {
                return Redirect::to('user/login')->with('error', 'Your account is Pending.')->withInput();
            } else {
                return Redirect::to('user/login')->with('error', 'Your account is inactive.')->withInput();
            }
        } else {
            return Redirect::to('user/login')->with('error', 'Invalid login.')->withInput();
        }
    }
    
    /**
     * Used for logout a user
     * 
     * @param void
     * @return void
     */
    public function getLogout()
    {
        Auth::logout();
        return Redirect::to('/')->with('message', 'Your are now logged out!');
    }
    public function getChangepassword()
    {
        $this->layout = View::make('layouts.admin');
        $layoutArr    = array();
        
        $this->layout->content = View::make('user.changepassword', array(
            'layoutArr' => $layoutArr
        ));
    }
    /**
     * Used for changing user password
     * 
     * @param void
     * @return void
     */
    public function postUpdatepassword()
    {
        $valiationArr = array();
        if (isset(Auth::user()->id) && Auth::user()->id) {
            $formData    = Input::all();
            $formDataArr = array();
            if (isset($formData['formdata']) && $formData['formdata'] != '') {
                parse_str($formData['formdata'], $formDataArr);
                if (isset($formDataArr['User']) && is_array($formDataArr['User']) && count($formDataArr['User']) > 0) {
                    $validator = Validator::make($formDataArr['User'], User::$rules['changepassword']);
                    if ($validator->fails()) {
                        $errorArr = $validator->getMessageBag()->toArray();
                        if (isset($errorArr) && is_array($errorArr) && count($errorArr) > 0) {
                            foreach ($errorArr as $errorKey => $errorVal) {
                                $valiationArr[] = array(
                                    'modelField' => $errorKey,
                                    'modelErrorMsg' => $errorVal[0]
                                );
                            }
                        }
                    }
                    if (isset($formDataArr['User']['old_password']) && $formDataArr['User']['old_password'] != '') {
                        $user = Auth::user();
                        if (!(Hash::check($formDataArr['User']['old_password'], $user->getAuthPassword()))) {
                            $valiationArr[] = array(
                                'modelField' => 'old_password',
                                'modelErrorMsg' => 'Old Password is incorrect.'
                            );
                        }
                    }
                    if (is_array($valiationArr) && count($valiationArr) > 0) {
                        echo '****FAILURE****' . json_encode($valiationArr);
                    } else {
                        DB::beginTransaction();
                        if (isset(Auth::user()->id) && Auth::user()->id != 0) {
                            $formDataArr['user']['updated_at'] = date('Y-m-d h:i:s');
                            $formDataArr['user']['password']   = Hash::make($formDataArr['User']['new_password']);
                            try {
                                DB::table('users')->where('id', Auth::user()->id)->update($formDataArr['user']);
                                DB::commit();
                                echo '****SUCCESS****User password has been changed successfully.';
                            }
                            catch (ValidationException $e) {
                                DB::rollback();
                                echo '****ERROR****Unable to change user password.';
                            }
                            catch (\Exception $e) {
                                DB::rollback();
                                echo '****ERROR****Unable to change user password.';
                            }
                        }
                    }
                }
            } else {
                echo '****ERROR****Invalid form submission.';
            }
        } else {
            echo '****ERROR****Please login to register.';
        }
        exit;
    }
    
    /**
     * Managing user profile
     * 
     * @param void
     * @return void
     */
    public function postProfileupdate()
    {
        $valiationArr = array();
        if (isset(Auth::user()->id) && Auth::user()->id) {
            $formData    = Input::all();
            $formDataArr = array();
            if (isset($formData['formdata']) && $formData['formdata'] != '') {
                parse_str($formData['formdata'], $formDataArr);
                if (isset($formDataArr['User']) && is_array($formDataArr['User']) && count($formDataArr['User']) > 0) {
                    $validator = Validator::make($formDataArr['User'], User::$rules['profile']);
                    if ($validator->fails()) {
                        $errorArr = $validator->getMessageBag()->toArray();
                        if (isset($errorArr) && is_array($errorArr) && count($errorArr) > 0) {
                            foreach ($errorArr as $errorKey => $errorVal) {
                                $valiationArr[] = array(
                                    'modelField' => $errorKey,
                                    'modelErrorMsg' => $errorVal[0]
                                );
                            }
                        }
                    }
                    if (is_array($valiationArr) && count($valiationArr) > 0) {
                        echo '****FAILURE****' . json_encode($valiationArr);
                    } else {
                        DB::beginTransaction();
                        if (isset(Auth::user()->id) && Auth::user()->id != 0) {
                            $formDataArr['user']['updated_at'] = date('Y-m-d h:i:s');
                            $formDataArr['user']['fullname']   = $formDataArr['User']['fullname'];
                            $formDataArr['user']['email']      = $formDataArr['User']['email'];
                            $formDataArr['user']['mobile']     = $formDataArr['User']['mobile'];
                            try {
                                DB::table('users')->where('id', Auth::user()->id)->update($formDataArr['user']);
                                DB::commit();
                                echo '****SUCCESS****User profile saved successfully.';
                            }
                            catch (ValidationException $e) {
                                DB::rollback();
                                echo '****ERROR****Unable save user profile.';
                            }
                            catch (\Exception $e) {
                                DB::rollback();
                                echo '****ERROR****Unable save user profile.';
                            }
                        }
                    }
                }
            } else {
                echo '****ERROR****Invalid form submission.';
            }
        } else {
            echo '****ERROR****Please login to register.';
        }
        exit;
    }
    /**
     * Used for showing profile info
     * 
     * @param void
     * @return void
     */
    public function getProfile()
    {
        $this->layout          = View::make('layouts.admin');
        $userArr               = Auth::user();
        $layoutArr             = array(
            'userArr' => $userArr
        );
        $this->layout->content = View::make('user.profile', array(
            'layoutArr' => $layoutArr
        ));
    }
    
    public function getProfileinfo()
    {
        $this->layout          = View::make('layouts.ajax');
        $userArr               = Auth::user();
        $layoutArr             = array(
            'userArr' => $userArr
        );
        $this->layout->content = View::make('user.profileinfo', array(
            'layoutArr' => $layoutArr
        ));
    }
    
    public function getLostpassword()
    {
        $this->layout->content = View::make('user.lostpassword');
    }
    public function postValidatelostpswdmail()
    {
        $valiationArr = array();
        $formData     = Input::all();
        $formDataArr  = array();
        $username     = '';
        if (isset($formData['formdata']) && $formData['formdata'] != '') {
            parse_str($formData['formdata'], $formDataArr);
            if (isset($formDataArr['User']) && is_array($formDataArr['User']) && count($formDataArr['User']) > 0) {
                $validator = Validator::make($formDataArr['User'], User::$rules['lostpasswordemail']);
                if ($validator->fails()) {
                    $errorArr = $validator->getMessageBag()->toArray();
                    if (isset($errorArr) && is_array($errorArr) && count($errorArr) > 0) {
                        foreach ($errorArr as $errorKey => $errorVal) {
                            $valiationArr[] = array(
                                'modelField' => $errorKey,
                                'modelErrorMsg' => $errorVal[0]
                            );
                        }
                    }
                } else {
                    if (isset($formDataArr['User']['email_forgot']) && $formDataArr['User']['email_forgot'] != '') {
                        $userArr = DB::table('users')->where('users.email', '=', $formDataArr['User']['email_forgot'])->select(array(
                            'users.id'
                        ))->first();
                        if (!is_object($userArr)) {
                            $valiationArr[] = array(
                                'modelField' => 'email_forgot',
                                'modelErrorMsg' => 'This email does not exist.'
                            );
                        }
                    }
                }
                if (is_array($valiationArr) && count($valiationArr) > 0) {
                    echo '****FAILURE****' . json_encode($valiationArr);
                    exit;
                } else {
                    DB::beginTransaction();
                    if (isset($userArr->id) && $userArr->id != 0) {
                        $userObj = DB::table('users')->where('users.email', '=', $formDataArr['User']['email_forgot'])->first();
                        if (is_object($userObj)) {
                            $fullname = $userObj->fullname;
                        }
                        global $to;
                        $to                                    = $formDataArr['User']['email_forgot'];
                        $formDataArr['user']['updated_at']     = date('Y-m-d h:i:s');
                        $formDataArr['user']['remember_token'] = csrf_token();
                        $formDataArr['user']['is_reset_req']   = 'Y';
                        try {
                            DB::table('users')->where('id', $userArr->id)->update($formDataArr['user']);
                            $data = array(
                                'csrf_token' => csrf_token(),
                                'id' => $userArr->id,
                                'fullname' => $fullname
                            );
                            
                            Mail::send('emails.resetpassword', $data, function($message)
                            {
                                global $to;
                                $confg   = Config::get('config');
                                $from    = $confg['mail']['from'];
                                $subject = $confg['mail']['reset_password'];
                                $message->from($from);
                                $message->to($to);
                                $message->subject($subject);
                            });
                            DB::commit();
                            echo '****SUCCESS****Reset password sent successfully. Please Check your Mail';
                            exit;
                        }
                        catch (ValidationException $e) {
                            DB::rollback();
                            echo '****ERROR****Unable to sent email.';
                            exit;
                        }
                        catch (\Exception $e) {
                            DB::rollback();
                            throw $e;
                            echo '****ERROR****Unable to sent email.';
                            exit;
                        }
                    }
                }
            }
        } else {
            echo '****ERROR****Invalid form submission.';
            exit;
        }
    }
    public function getResetpassword($token = '', $id = 0)
    {
        $username = '';
        $error    = '';
        if ($token != '' && (int) $id != 0) {
            $userCnt = DB::table('users')->where('users.id', '=', (int) $id)->where('users.is_reset_req', '=', 'Y')->where('users.remember_token', '=', $token)->count();
            if ($userCnt == 0) {
                $error = 'This link is invalid.';
            }
        }
        $layoutArr             = array(
            'token' => $token,
            'id' => $id,
            'error' => $error,
            'username' => $username
        );
        $this->layout->content = View::make('user.resetpassword', array(
            'layoutArr' => $layoutArr
        ));
    }
    /**
     * Managing reset password
     * 
     * @param void
     * @return void
     */
    public function postResetpassword()
    {
        $valiationArr = array();
        $formData     = Input::all();
        $formDataArr  = array();
        
        if (isset($formData['formdata']) && $formData['formdata'] != '') {
            parse_str($formData['formdata'], $formDataArr);
            if (isset($formDataArr['User']) && is_array($formDataArr['User']) && count($formDataArr['User']) > 0) {
                $validator = Validator::make($formDataArr['User'], User::$rules['resetpassword'], User::$messages);
                if ($validator->fails()) {
                    $errorArr = $validator->getMessageBag()->toArray();
                    if (isset($errorArr) && is_array($errorArr) && count($errorArr) > 0) {
                        foreach ($errorArr as $errorKey => $errorVal) {
                            $valiationArr[] = array(
                                'modelField' => $errorKey,
                                'modelErrorMsg' => $errorVal[0]
                            );
                        }
                    }
                } else {
                    if (isset($formDataArr['User']['id']) && $formDataArr['User']['id'] != '') {
                        $userCnt = DB::table('users')->where('users.id', '=', $formDataArr['User']['id'])->where('users.is_reset_req', '=', 'Y')->where('users.remember_token', '=', $formDataArr['User']['token'])->count();
                        if ($userCnt == 0) {
                            echo '****ERROR****This link is invalid';
                        }
                    }
                }
                if (is_array($valiationArr) && count($valiationArr) > 0) {
                    echo '****FAILURE****' . json_encode($valiationArr);
                } else {
                    DB::beginTransaction();
                    if (isset($userCnt) && $userCnt != 0) {
                        $formDataArr['user']['password']       = Hash::make($formDataArr['User']['new_password']);
                        $formDataArr['user']['updated_at']     = date('Y-m-d h:i:s');
                        $formDataArr['user']['remember_token'] = '';
                        $formDataArr['user']['is_reset_req']   = 'N';
                        try {
                            DB::table('users')->where('id', $formDataArr['User']['id'])->update($formDataArr['user']);
                            DB::commit();
                            echo '****SUCCESS****Password reset successfully.';
                        }
                        catch (ValidationException $e) {
                            DB::rollback();
                            echo '****ERROR****Unable to reset password.';
                        }
                        catch (\Exception $e) {
                            DB::rollback();
                            echo '****ERROR****Unable to reset password.';
                        }
                    }
                }
            }
        } else {
            echo '****ERROR****Invalid form submission.';
        }
        exit;
    }
    public function getForgotpassword()
    {
        $this->layout          = View::make('layouts.register');
        $this->layout->content = View::make('user.forgotpassword');
    }
    public function getResetlostpassword($token = '', $id = 0)
    {
        $this->layout = View::make('layouts.register');
        $username     = '';
        $error        = '';
        if ($token != '' && (int) $id != 0) {
            $userCnt = DB::table('users')->where('users.id', '=', (int) $id)->where('users.is_reset_req', '=', 'Y')->where('users.remember_token', '=', $token)->count();
            if ($userCnt == 0) {
                $error = 'This link is invalid.';
            }
        }
        $layoutArr             = array(
            'token' => $token,
            'id' => $id,
            'error' => $error,
            'username' => $username
        );
        $this->layout->content = View::make('user.resetlostpassword', array(
            'layoutArr' => $layoutArr
        ));
    }
    public function getUserregister()
    {
        $this->layout = View::make('layouts.register');
        if (isset(Auth::user()->id) && Auth::user()->id != 0) {
            return Redirect::to('censors/dashboard')->with('message', 'You are now logged in!');
        } else {
            $this->layout->content = View::make('user.userregister');
        }
    }
    public function postAddnewregistration()
    {
        $valiationArr = array();
        $formData     = Input::all();
        $formDataArr  = array();
        if (isset($formData['formdata']) && $formData['formdata'] != '') {
            parse_str($formData['formdata'], $formDataArr);
            if (isset($formDataArr['User']) && is_array($formDataArr['User']) && count($formDataArr['User']) > 0) {
                $validator = Validator::make($formDataArr['User'], User::$rules['signup'], User::$messages);
                if ($validator->fails()) {
                    $errorArr = $validator->getMessageBag()->toArray();
                    if (isset($errorArr) && is_array($errorArr) && count($errorArr) > 0) {
                        foreach ($errorArr as $errorKey => $errorVal) {
                            $valiationArr[] = array(
                                'modelField' => $errorKey,
                                'modelErrorMsg' => $errorVal[0]
                            );
                        }
                        echo '****FAILURE****' . json_encode($valiationArr);
                        exit;
                    }
                } else {
                    DB::beginTransaction();
                    $loopCnt         = 0;
                    $saveCnt         = 0;
                    $role_id         = 4;
                    $organisation_id = 1;
                    $csrf_token      = csrf_token();
                    $date            = date('Y-m-d');
                    $newDate         = strtotime(date("Y-m-d", strtotime($date)) . " +15 day");
                    $mailExpiredDate = date("Y-m-d", $newDate);
                    if (isset($formDataArr['User']['is_active']) && $formDataArr['User']['is_active'] != '') {
                        $status = $formDataArr['User']['is_active'];
                    } else {
                        $status = 'Y';
                    }
                    $getSubscriptionObj = DB::table('subscriptions')->orderby('subscriptions.id', 'asc')->first();
                    if (is_object($getSubscriptionObj)) {
                        $subscriptionId    = $getSubscriptionObj->id;
                        $subscription_days = $getSubscriptionObj->subscription_days;
                        $date              = date('Y-m-d');
                        $newDate           = strtotime(date("Y-m-d", strtotime($date)) . " +" . $subscription_days . " day");
                        $expiredDate       = date("Y-m-d", $newDate);
                    }
                    $tblCustIdCnt = DB::table('users')->count();
                    $subject      = "Verify your email address";
                    if ($tblCustIdCnt == 0) {
                        $customer_id = 10000;
                    } else {
                        $tblCustIdObj = DB::table('users')->orderby('users.id', 'desc')->first();
                        if (is_object($tblCustIdObj)) {
                            $customer_id = $tblCustIdObj->customer_id + 1;
                        }
                    }
                    $fullname  = trim($formDataArr['User']['fullname']);
                    $email     = trim($formDataArr['User']['email']);
                    $tblObjCnt = DB::table('users')->where('users.fullname', '=', $fullname)->where('users.email', '=', $email)->count();
                    if ($tblObjCnt == 0) {
                        try {
                            $loopCnt++;
                            $formDataArr['User']['role_id']           = $role_id;
                            $formDataArr['User']['organisation_id']   = $organisation_id;
                            $formDataArr['User']['fullname']          = $fullname;
                            $formDataArr['User']['customer_id']       = $customer_id;
                            $formDataArr['User']['email']             = $email;
                            $formDataArr['User']['is_active']         = "Y";
                            $formDataArr['User']['is_reset_req']      = "N";
                            $formDataArr['User']['is_mail_req']       = "Y";
                            $formDataArr['User']['expired_date']      = $expiredDate;
                            $formDataArr['User']['mail_expired_date'] = $mailExpiredDate;
                            $formDataArr['User']['remember_token']    = $csrf_token;
                            $formDataArr['User']['password']          = Hash::make($formDataArr['User']['password']);
                            $formDataArr['User']['created_at']        = date('Y-m-d h:i:s');
                            $formDataArr['User']['updated_at']        = date('Y-m-d h:i:s');
                            unset($formDataArr['User']['password_confirmation']);
                            $user_id = DB::table('users')->insertGetId($formDataArr['User']);
                            $saveCnt++;
                        }
                        catch (ValidationException $e) {
                            DB::rollback();
                        }
                        catch (\Exception $e) {
                            DB::rollback();
                        }
                        if (isset($user_id) && $user_id != '') {
                            try {
                                $loopCnt++;
                                $formDataArr['UserPlanSubscription']['user_id']         = $user_id;
                                $formDataArr['UserPlanSubscription']['subscription_id'] = $subscriptionId;
                                $formDataArr['UserPlanSubscription']['register_date']   = $date;
                                $formDataArr['UserPlanSubscription']['expired_date']    = $expiredDate;
                                $formDataArr['UserPlanSubscription']['created_at']      = date('Y-m-d h:i:s');
                                $formDataArr['UserPlanSubscription']['updated_at']      = date('Y-m-d h:i:s');
                                DB::table('user_plan_subscriptions')->insertGetId($formDataArr['UserPlanSubscription']);
                                $saveCnt++;
                            }
                            catch (ValidationException $e) {
                                DB::rollback();
                            }
                            catch (\Exception $e) {
                                DB::rollback();
                            }
                        }
                        global $to;
                        $to = $formDataArr['User']['email'];
                        try {
                            $data = array(
                                'csrf_token' => $csrf_token,
                                'user_id' => $user_id,
                                'fullname' => $formDataArr['User']['fullname'],
                                'subject' => $subject,
                                'customerId' => $customer_id
                            );
                            
                            Mail::send('emails.mailconformation', $data, function($message)
                            {
                                global $to;
                                $confg   = Config::get('config');
                                $from    = $confg['mail']['from'];
                                $subject = $confg['mail']['mail_conformation'];
                                $message->from($from);
                                $message->to($to);
                                //$message->cc('rashmirdas.mca@gmail.com');
                                $message->subject($subject);
                            });
                            
                        }
                        catch (ValidationException $e) {
                            DB::rollback();
                        }
                        catch (\Exception $e) {
                            DB::rollback();
                            throw $e;
                        }
                        if ($loopCnt = $saveCnt) {
                            DB::commit();
                            echo '****SUCCESS****User has been Created successfully.Please Check your mail.';
                        } else {
                            DB::rollback();
                            echo '****ERROR****Unable to save data.';
                        }
                    } else {
                        echo '****ERROR****This User is already exist.';
                    }
                }
            }
        }
        exit;
    }
    public function getUsermailvalidation($token = '', $id = 0)
    {
        $this->layout      = View::make('layouts.register');
        $id                = base64_decode(base64_decode($id));
        $name              = '';
        $email             = '';
        $error             = '';
        $mailNotVerifyCnt  = 0;
        $acexpiredCnt      = 0;
        $userAcntVerifyCnt = 0;
        $userId            = base64_encode(base64_encode(50));
        if ($token != '' && (int) $id != 0) {
            $userDatasCnt = DB::table('users')->where('users.id', '=', (int) $id)->where('users.remember_token', '=', '')->where('users.is_active', '=', 'Y')->count();
            if ($userDatasCnt == 0) {
                $userDataObj = DB::table('users')->where('users.id', '=', (int) $id)->where('users.remember_token', '=', $token)->first();
                if (is_object($userDataObj)) {
                    $name    = $userDataObj->fullname;
                    $email   = $userDataObj->email;
                    $userCnt = DB::table('users')->where('users.id', '=', (int) $id)->where('users.is_mail_req', '=', 'Y')->where('users.remember_token', '=', $token)->count();
                    if ($userCnt == 0) {
                        $mailNotVerifyCnt++;
                    }
                } else {
                    $userObj = DB::table('users')->where('users.id', '=', (int) $id)->where('users.is_active', '=', 'N')->first();
                    if (is_object($userObj)) {
                        $name = $userObj->fullname;
                    }
                    $acexpiredCnt++;
                }
            } else {
                $userObj = DB::table('users')->where('users.id', '=', (int) $id)->where('users.is_active', '=', 'Y')->first();
                if (is_object($userObj)) {
                    $name = $userObj->fullname;
                }
                $userAcntVerifyCnt++;
            }
        }
        $layoutArr = array(
            'token' => $token,
            'id' => $id,
            'error' => $error,
            'name' => $name,
            'email' => $email
        );
        if ($mailNotVerifyCnt == 0 && $acexpiredCnt == 0 && $userAcntVerifyCnt == 0) {
            $this->layout->content = View::make('user.usermailvalidation', array(
                'layoutArr' => $layoutArr
            ));
        } else if ($mailNotVerifyCnt == 0 && $acexpiredCnt > 0 && $userAcntVerifyCnt == 0) {
            $this->layout->content = View::make('user.useraccountexpired', array(
                'layoutArr' => $layoutArr
            ));
        } else if ($mailNotVerifyCnt == 0 && $acexpiredCnt == 0 && $userAcntVerifyCnt > 0) {
            $this->layout->content = View::make('user.usermailverified', array(
                'layoutArr' => $layoutArr
            ));
        }
    }
    public function postVerifylogin()
    {
        $valiationArr = array();
        $formData     = Input::all();
        $formDataArr  = array();
        if (isset($formData['formdata']) && $formData['formdata'] != '') {
            parse_str($formData['formdata'], $formDataArr);
            if (isset($formDataArr['id']) && $formDataArr['id'] != '') {
                $id    = $formDataArr['id'];
                $token = $formDataArr['verfy_token'];
                DB::beginTransaction();
                $loopCnt = 0;
                $saveCnt = 0;
                $userCnt = DB::table('users')->where('users.id', '=', (int) $id)->where('users.is_mail_req', '=', 'Y')->where('users.remember_token', '=', $token)->count();
                if ($userCnt > 0) {
                    try {
                        $loopCnt++;
                        $formDataArr['User']['is_mail_req']       = "N";
                        $formDataArr['User']['remember_token']    = "";
                        $formDataArr['User']['mail_expired_date'] = "0000-00-00";
                        $formDataArr['User']['updated_at']        = date('Y-m-d h:i:s');
                        DB::table('users')->where('id', $id)->update($formDataArr['User']);
                        $saveCnt++;
                    }
                    catch (ValidationException $e) {
                        DB::rollback();
                    }
                    catch (\Exception $e) {
                        DB::rollback();
                    }
                }
                if ($loopCnt = $saveCnt) {
                    DB::commit();
                    echo '****SUCCESS****Your Email Successfully veryfied';
                } else {
                    DB::rollback();
                    echo '****ERROR****Unable to save data.';
                }
            }
        }
        exit;
    }
}
?> 
