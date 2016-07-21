<?php

/**
 * SocialController File
 *
 * PHP version 5.6
 *
 * @category  Social login
 * @package   login
 * @author    Yuvaraja <yuvaaji@example.com>
 * @copyright 2016 example.com
 * @license   https://www.example.com/license/1_0.txt License 1.0
 * @link      https://www.example.com/
 */

/**
 * SocialController Class
 *
 * @category  Social login
 * @package   login
 * @author    Yuvaraja <yuvaaji@example.com>
 * @copyright 2016 example.com
 * @license   https://www.example.com/license/1_0.txt License 1.0
 * @link      https://www.example.com/
 */
class SocialController extends \BaseController {

    public function getFacebook() {


        // get data from input
        $code = Input::get('code');

        // get fb service
        $fb = OAuth::consumer('Facebook');
        // check if code is valid
        // if code is provided get user data and sign in
        if (!empty($code)) {

            // This was a callback request from facebook, get the token
            $token = $fb->requestAccessToken($code);

            // Send a request with it
            $result = json_decode($fb->request('/me'), true);

            $user = User::leftJoin('user_credentials AS c', 'c.user_id', '=', 'users.id')->where('email', '=', $result['email'])->first();

            if (!$user) {
                
                $Password = 'Password@123';//str_random(8);

                $user = new User;
                $user->name = $result['first_name'];
                $user->save();
                
                $user_credential = [
                    'user_id' => $user->id,
                    'email' => $result['email'],
                    'password' => Hash::make($Password),
                    'registered_from' => 'Facebook',
                ];
                
                UserCredential::insert($user_credential);
                //mailing
            } 
            
            if (!Auth::check()) {
                Auth::login($user);
            }
            
            return Redirect::to('/dashboard');
        }
        // if not ask for permission first
        else {
            // get fb authorization
            $url = $fb->getAuthorizationUri();

            // return to facebook login url
            return Redirect::to((string) $url);
        }
    }

    /**
     * To check whether the login details are authenticated or not using google login
     *
     * @return blade file
     */
    public function getGoogle() {

        $code = Input::get('code');
        $googleService = OAuth::consumer('Google');
        
        if (!empty($code)) {

            $token = $googleService->requestAccessToken($code);
            $result = json_decode($googleService->request('https://www.googleapis.com/oauth2/v1/userinfo'), true);

            $user = User::leftJoin('user_credentials AS c', 'c.user_id', '=', 'users.id')->where('email', '=', $result['email'])->first();

            if (!$user) {
                
                $Password = 'Password@123';//str_random(8);

                $user = new User;
                $user->name = $result['first_name'];
                $user->save();
                
                $user_credential = [
                    'user_id' => $user->id,
                    'email' => $result['given_name'],
                    'password' => Hash::make($Password),
                    'registered_from' => 'Google',
                ];
                
                UserCredential::insert($user_credential);

                //mailing
            } 
            
            if (!Auth::check()) {
                Auth::login($user);
            }
            
            return Redirect::to('/dashboard');
        } else {
            $url = $googleService->getAuthorizationUri();
            return Redirect::to((string) $url);
        }
    }

}

//end class
