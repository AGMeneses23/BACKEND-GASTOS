<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Sobrescribir la función de inicio de sesión para verificar el rol del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    protected function authenticated(Request $request, $user)
    {
        //VERIFICA SI EL USUARIO TIENE EL ROL DE 'USER'
        if ($user->rol === 'user') {

            //CERRAR SESION AUTOMATICAMENTE SI ES UN USUARIO NORMAL
            Auth::logout();

            //REDIRIGIR CON UN MENSAJE DE ERROR
            return redirect('/login')->withErrors(['error' => 'Acceso denegado. No tienes permiso para acceder al panel de administración.']);
        }

        //SI EL USUARIO ES ADMIN, PROCEDER CON NORMALIDAD
        return redirect()->intended($this->redirectTo);
    }
}
