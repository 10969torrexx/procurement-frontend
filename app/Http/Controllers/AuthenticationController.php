<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\MiscellaneousController;
use Illuminate\Support\Facades\DB;
use Socialite;

class AuthenticationController extends Controller
{
  //Login page
  // public function loginPage(){
  //   $pageConfigs = ['bodyCustomClass'=> 'bg-full-screen-image'];
  //   return view('pages.auth-login',['pageConfigs' => $pageConfigs]);
 // }
  //Register page
  public function registerPage(){
    $pageConfigs = ['bodyCustomClass'=> 'bg-full-screen-image'];
    return view('pages.auth-register',['pageConfigs' => $pageConfigs]);
  }
   //forget Password page
   public function forgetPasswordPage(){
    $pageConfigs = ['bodyCustomClass'=> 'bg-full-screen-image'];
    return view('pages.auth-forgot-password',['pageConfigs' => $pageConfigs]);
  }
   //reset Password page
   public function resetPasswordPage(){
    $pageConfigs = ['bodyCustomClass'=> 'bg-full-screen-image'];
    return view('pages.auth-reset-password',['pageConfigs' => $pageConfigs]);
  }
   //auth lock page
   public function authLockPage(){
    $pageConfigs = ['bodyCustomClass'=> 'bg-full-screen-image'];
    return view('pages.auth-lock-screen',['pageConfigs' => $pageConfigs]);
  }
  public function adminlogin(Request $request){
      $username = $request->username;
      $password = $request->password;
      $login = Http::post(env('APP_API'). "/api/login", [
        'username' => $username,
        'password' => $password,
      ])->json();
       
     if($login){
        if ($login['status'] == 200) {
          session([
            'token' => $login['data']['token'],
            'username' => $login['data']['username'],
            'campus' => $login['data']['campus'],
            'name' => $login['data']['name'],
            'department_id' => $login['data']['department_id'],

          ]);
          return redirect('/');
        }
        
        if($login['status'] == 400){
          $error = $login['message'];
         // return redirect()->route('admin.login')->with( ['error' => $error ] );
          return view('pages.auth-lock-screen', compact('error'));
        }
      }
  }
  //frontend
  public function logout(){
   
    try{
      $logout = Http::withToken(session('token'))->post(env('APP_API') . "/api/logout")->json();
      session()->flush();
      return redirect('/login');
    }catch(Exception $user){
      session()->flush();
      return redirect('/login');
    }
    

  }
  
  public function loginPage()
  {
    if (!empty(session('token'))) {
      return redirect('/');
    }
    $pageConfigs = ['bodyCustomClass' => 'bg-full-screen-image'];
    return view('pages.auth-login', ['pageConfigs' => $pageConfigs]);
  }

  public function redirectToGoogle()
  {
    return Socialite::driver('google')->redirect();
  }

  public function handleGoogleCallback()
  {

    try {
      $user = Socialite::driver('google')->user(); 
	    // dd($user);
      if($user){
        
        $name = $user->name;
        $email = $user->email;
        $photo = $user->avatar;
	  // //	$checkEmail = Http::withToken('22944|ftDmaRgt3EZbCqTmgRgxGpLsODEglYQzSVSxy8zq')->post("https://api.southernleytestateu.edu.ph/api/sadmin/auth/checkemail",[  "email" => $email ])->json();
	  // 	  $checkEmail = Http::post("https://api.southernleytestateu.edu.ph/api/isauth/checkemail",["email" => $email ])->json();

        $checkEmail = DB::connection("hrmis")
                  ->table("employee")
                  ->where('EmailAddress', $email)
                  ->whereNull("deleted_at")
                  ->first();
      //  dd($checkEmail);
        if(!empty($checkEmail)){

         // if($checkEmail){
          //  if($checkEmail['status'] == 200){
                $employee_id = $checkEmail->id;
                $position = $checkEmail->EmploymentStatus;
               
                $middle_name = $checkEmail->MiddleName;
                $middle_initial = substr($middle_name, 0, 1).'.';
                $name = ucfirst(strtolower($checkEmail->FirstName)).' '.$middle_initial.' '.ucfirst(strtolower($checkEmail->LastName));
                $data = array();

                $login = Http::post(env('APP_API'). "/api/loginusinggoogle",[
                  'name' => $name,
                  'email' => $email,
                  'photo' => $photo,
                  'employee_id' =>$employee_id,
                  'position' =>$position,
                  'campus' => $checkEmail->Campus
                  ])->json();
                //  dd($login);

                if($login['status'] == 200){
                    $login = $login['data'];
                    session([
                      'token' => $login['token'] ,
                      'name' => $login['name'],
                      'role' => $login['role'],
                      'photo' => $photo,
                      'campus' =>   $login['campus'],
                      /** Torrexx Additionals */
                        'department_id' => $login['department_id'],
                        'user_id' => $login['user_id'],
                        'employee_id' =>  $login['employee_id'],
                        'position' =>  $login['position'],
                        'immediate_supervisor'  =>   $login['immediate_supervisor'],
                    ]);
  
                  return redirect('/');
                 }
                
                 session(['globalerror' => "Please try again"]);
                return redirect("/login");
            }
            session(['globalerror' => "User not found. Kindly visit HRM Office for registration"]);
            return redirect("/login");
      }
    } catch (\Throwable $th) {
        session(['globalerror' => "Please try again"]);
        return redirect("/login");
    }
   } 
}
