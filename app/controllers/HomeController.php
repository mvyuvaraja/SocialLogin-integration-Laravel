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

                if (Auth::attempt(array('email' => $email, 'password' => Input::get('password')), false)) {

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
                'email' => 'required|email|unique:users',
                'password' => 'required'
            );

            $validator = Validator::make(Input::all(), $rules);
            $name = trim(Input::get('name'));
            $email = trim(Input::get('email'));
            $Password = trim(Input::get('password'));

            if (filter_var($email, FILTER_VALIDATE_EMAIL) && $validator->passes()) {

                $user = new User;
                $user->name = $name;
                $user->email = $email;
                $user->password = Hash::make($Password);
                $user->save();
                
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

	public function getLogout()
	{        
        Auth::logout();
        return Redirect::to('/login');
	}

}
