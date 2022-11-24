<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AESCipher;
use App\Http\Controllers\MiscellaneousController;
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
      // dd($login);
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
    $logout = Http::withToken(session('token'))->post(env('APP_API') . "/api/logout")->json();
    if (!empty($logout)) {
      if ($logout['status'] == 200) {
        session()->flush();
        return redirect('/login');
      }
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
      
      if($user){
        $name = $user->name;
        $email = $user->email;
        $photo = $user->avatar;
	    $checkEmail = Http::withToken('14240|DCz8jRB7WZUZswmk7tlIfYcQwkdNUiwYJcjC0F7z')->post("https://api.southernleytestateu.edu.ph/api/auth/checkemail", [  "email" => $email ])->json();
       dd($checkEmail );
        $login = Http::post(env('APP_API'). "/api/loginbygoogle",[
          'name' => $name,
          'email' => $email,
          'photo' => $photo
          ])->json();

         dd($login);
        if(!empty($login)){
          if($login['status'] == 200){
            session([
              'token' => $login['data']['token'],
              'name' => $login['data']['name'],
              'role' => $login['data']['role'],
              'photo' => $user->avatar,
              'campus' => $login['data']['campus'],
              /** Torrexx Additionals */
                'department_id' => $login['data']['department_id'],
                'user_id' => $login['data']['user_id'],
                'employee_id' => $login['data']['employee_id'],
                'immediate_supervisor'  =>  $login['data']['immediate_supervisor'],
            ]);
            return redirect('/');
         }
         
        }
        /* Torrexx Additionals */
          # this will show a di
          // $pageConfigs = ['bodyCustomClass' => 'bg-full-screen-image'];
          // return view('pages.auth-login', ['pageConfigs' => $pageConfigs]);
      }
    } catch (\Throwable $th) {
      /* Torrexx Additionals */  
        // this will return error 404 page from Miscellaneous Controller
        return (new MiscellaneousController)->error500Page();
        // throw $th;
    }
   } 
}
