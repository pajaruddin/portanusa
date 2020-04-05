<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Auth;

use Config;
use App\User;
use App\Login_attempt;

class AuthUser {

    protected static $message;

    public static function firstName() {
        return Auth::user()->first_name;
    }

    public static function lastName() {
        return Auth::user()->last_name;
    }

    public static function fullName() {
        return Auth::user()->first_name." ".Auth::user()->last_name;
    }

    public static function login($email, $password){

      $user = User::where('email',$email)->where('active',1)->first();

      if(empty($user)){
        self::$message = "The email you entered is not listed in our system";
      }else{

        if(self::is_time_locked_out($email)){
          self::$message = "For a while your account is locked, please try again after 5 minutes";
        }else{

          if(self::is_max_login_attempts_exceeded($email)){
            self::clear_login_attempts($email);
          }

          $salt = $user->salt;
          $encrypt_password = sha1($password.$salt);

          if ($encrypt_password == $user->password) {
            $login = Auth::loginUsingId($user->id);
            if ($login) {
              self::clear_login_attempts($email);
              return TRUE;
            }else{
                self::$message = "Login Unsuccessful";
            }

          }else{
            self::$message = "Password does not match";
            self::increase_login_attempts($email);
          }
        }
      }

      return FALSE;
    }

    public static function is_time_locked_out($email){
      return self::is_max_login_attempts_exceeded($email) && self::get_last_attempt_time($email) > time() - Config::get('user_auth.lockout_time');
    }

    public static function is_max_login_attempts_exceeded($email){
      $max_attempts = Config::get('user_auth.maximum_login_attempts');
      if ($max_attempts > 0) {
        $attempts = self::get_attempts_num($email);
        return $attempts >= $max_attempts;
      }
    }

    public static function get_attempts_num($email){
      $ip_address = \Request::ip();
      $get_attempt = Login_attempt::where('email',$email)->where('ip_address', $ip_address)->get();
      return count($get_attempt);
    }

    public static function get_last_attempt_time($email){
      $ip_address = \Request::ip();
      $get_attempt = Login_attempt::where('email',$email)->where('ip_address', $ip_address)->orderBy('time','desc')->first();
      return $get_attempt->time;
    }

    public static function clear_login_attempts($email){
      $ip_address = \Request::ip();
      $get_attempt = Login_attempt::where('email',$email)->where('ip_address', $ip_address);
      return $get_attempt->delete();
    }

    public static function increase_login_attempts($email){
      $login_attempt = new Login_attempt();
      $login_attempt->ip_address = \Request::ip();
      $login_attempt->email = $email;
      $login_attempt->time = time();

      return $login_attempt->save();
    }

    public static function get_message(){
      return self::$message;
    }

}
