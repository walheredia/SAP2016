<?php

	class UsuariosController extends BaseController {

	public function get_nuevo(){
		$user = Auth::user();
			if ($user->tipo_usuario === 3) {
				return View::make('register');
			}
			else
			{
				return Redirect::to('/')->with('error', $user->username)->withInput();
				//return "El usuario: '" . $user->username . "' no tiene privilegios suficientes para acceder al sitio que desea";
			}
	}
	
	public function post_nuevo()
	{
		$inputs = Input::all();
		$reglas = array(
			'first_name' => 'required|min:4', 
			'last_name' => 'required',
			'email' => 'email|unique:users,email',
			'username' => 'required|unique:users,username',
			'password' => 'required|min:5|max:20',
			'confirmar_clave' => 'required|same:password',
		);
		$mensajes = array(
			'required' => 'Campo Obligatorio',
		);
		$validar = Validator::make($inputs, $reglas);
		if($validar->fails())
		{	
			Input::flash();
			return Redirect::back()->withInput()->withErrors($validar);
		}
		else
		{
			$clave = Input::get('password');
			$user = new User;
			$user->first_name = Input::get('first_name');
			$user->last_name = Input::get('last_name');
			$user->email = Input::get('email');
			$user->username = Input::get('username');
			$user->password = Hash::make($clave);
			$user->tipo_usuario = Input::get('tipo_usuario');
			
	        $user->save();
			return Redirect::to('lista_usuarios')->with('error', 'El usuario ha sido registrado con Éxito')->withInput();
		}
	}
	public function destroy($id)
	{	
	$user = User::find($id);	        
	        if (is_null ($user))
	        {
	            App::abort(404);
	        }
	        $user->delete();
	        $users = User::all();
	        return View::make('lista_usuarios')->with('users', $users);
	}

	public function all_users() {
		$users = User::all();
		return View::make('lista_usuarios')->with('users', $users);
		
	}
	public function getEditUsuario($id) {
		$usera = Auth::user();
			if ($usera->tipo_usuario === 3) {
				$user = User::find($id);
				if (is_null ($user))
				{
				App::abort(404);
				}
				return View::make('edit_user')->with('user',$user);
			}
			else
			{
				return Redirect::to('/')->with('error', $usera->username)->withInput();
			}
		
	}
	public function update() {
		$inputs = Input::all();
		$reglas = array(
			'first_name' => 'required|min:4', 
			'last_name' => 'required',
			'email' => 'email',
			'username' => 'required',
			'password' => 'required|min:5|max:20',
			'confirmar_clave' => 'required|same:password',
		);
		$mensajes = array(
			'required' => 'Campo Obligatorio',
		);
		$validar = Validator::make($inputs, $reglas);
		if($validar->fails())
		{	
			Input::flash();
			return Redirect::back()->withInput()->withErrors($validar);
		}
		else
		{
			$id_user = Input::get('id');
			$user = User::find($id_user);

			$clave = Input::get('password');
			$user->first_name = Input::get('first_name');
			$user->last_name = Input::get('last_name');
			$user->email = Input::get('email');
			$user->username = Input::get('username');
			$user->password = Hash::make($clave);
			$user->tipo_usuario = Input::get('tipo_usuario');
			$user->save();
			return Redirect::to('lista_usuarios')->with('error', 'El usuario ha sido actualizado con Éxito')->withInput();
			//$users = User::all();
			//return View::make('lista_usuarios')->with('users', $users);
		}
	}
	
}