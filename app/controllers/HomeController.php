<?php

class HomeController extends \BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function getIndex()
	{
        if(Auth::check())
		    return Redirect::to('/dashboard');
        else
		    return Redirect::to('/login');
	}

	public function anyLogin()
	{
        if(Request::method() === 'GET'){
            
		    return View::make('login');
        } else {           

            $rules = array(
                'email' => 'required|email',
                'password' => 'required'
            );

            $validator = Validator::make(Input::all(), $rules);
            $email = trim(Input::get('email'));

            if (filter_var($email, FILTER_VALIDATE_EMAIL) && $validator->passes()) {

                $user = User::leftJoin('user_credentials AS c', 'c.user_id', '=', 'users.id')
                            ->where('email', '=', $email)
                            ->first();
                
                if ($user && Hash::check(Input::get('password'), $user->password)) {
                    Auth::login($user);
                    return Response::json(['valid' => 1], 200);
                }

                return Response::json(array('valid' => 0, 'message' => 'Invalid credentials'));
            }

            return Response::json(array('valid' => 0, 'errors' => $validator->errors()->toArray(), 'message' => 'Errors occured')); 
        }
	}

	public function anyRegister()
	{
        if(Request::method() === 'GET'){
            
		    return View::make('register');
        } else {           

            $rules = array(
                'name' => 'required',
                'email' => 'required|email|unique:user_credentials',
                'password' => 'required'
            );

            $validator = Validator::make(Input::all(), $rules);
            $name = trim(Input::get('name'));
            $email = trim(Input::get('email'));
            $Password = trim(Input::get('password'));

            if (filter_var($email, FILTER_VALIDATE_EMAIL) && $validator->passes()) {

                $user = new User;
                $user->name = $name;
                $user->save();
                
                $user_credential = [
                    'user_id' => $user->id,
                    'email' => $email,
                    'password' => Hash::make($Password),
                    'registered_from' => 'UI'
                ];
                
                UserCredential::insert($user_credential);
                
                //mailing
                
                Auth::login($user);

                return Response::json(array('valid' => 1, 'message' => 'Account created successfully.'));
            }

            return Response::json(array('valid' => 0, 'errors' => $validator->errors()->toArray(), 'message' => 'Errors occured')); 
        }
	}

	public function getDashboard()
	{
        if(!Auth::check())
		    return Redirect::to('/login');
		return View::make('dashboard');
	}

	public function anyAddSocialLoginAccount()
	{
        if(Request::method() === 'GET'){
            if(!Auth::check())
                return Redirect::to('/login');
                
            return View::make('add_social_login');
            
        } else {          

            $rules = array(
                'registered_from' => 'required',
                'email' => 'required|email|unique:user_credentials',
                'password' => 'required'
            );

            $validator = Validator::make(Input::all(), $rules);
            $registered_from = trim(Input::get('registered_from'));
            $email = trim(Input::get('email'));
            $Password = trim(Input::get('password'));

            if (filter_var($email, FILTER_VALIDATE_EMAIL) && $validator->passes()) {

                $user = UserCredential::where('email', '=', $email)->first();
                
                $user_credential = [
                    'user_id' => Auth::user()->id,
                    'email' => $email,
                    'password' => Hash::make($Password),
                    'registered_from' => $registered_from
                ];

                if (!$user) {
                    UserCredential::insert($user_credential);                
                }else{
                    if(Auth::user()->id == $user->id){
                        UserCredential::update($user_credential);
                    }else{
                        return Response::json(array('valid' => 0, 'message' => 'Account already associated with someother person.'));
                    }
                }
                
                return Response::json(array('valid' => 1, 'message' => 'Account created successfully.'));
            }

            $errors = array_flatten($validator->errors()->toArray());
            $errors = implode(' ', $errors);
            return Response::json(array('valid' => 0, 'message' => 'Errors occured. '.$errors)); 
              
        }
	}

	public function getLogout()
	{        
        Auth::logout();
        return Redirect::to('/login');
	}

}
