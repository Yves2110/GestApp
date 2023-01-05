<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller

{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()

    {
        return view('auth.login');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */

    public function registration()
    {
        return view('auth.register');
    }
    /**
     * Write code on Method
     *
     * @return response()
     */

    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            switch ((int) Auth::user()->role_id) {
                case 1:
                    $request->session()->regenerate();
                    return redirect()->route('Home');
                    break;
                case 2:
                    return redirect()->route('Home');
                    break;
                case 3:
                    return redirect()->route('Home');
                    break;
                case 4:
                    return redirect()->route('Home');
                    break;
            };
            Alert::success('Connexion établie', 'Bienvenue sur la plate-forme');
        } else return Redirect::back()->with('error', 'Email ou mot de passe incorrect!');
    }

    /**

     * Write code on Method
     *
     * @return response()
     */

    public function postRegistration(Request $request)

    {
        request()->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'birthday' => 'required',
            'sub' => 'required',
            'email' => 'required|email|unique:users',
            'tel' => 'required|min:8 ',
        ]);
        User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'birthday' => $request->birthday,
            'sub' => $request->sub,
            'email' => $request->email,
            'tel' => $request->tel,

        ]);
        return redirect("Home")->withSuccess('Enregistrement réussi!');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */

    public function dashboard()

    {

        if (Auth::check()) {
            return view('dashboard');
        }
        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */

    public function create(array $data)

    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return Redirect('home');
    }
}
