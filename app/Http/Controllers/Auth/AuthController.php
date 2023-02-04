<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\role;
use App\Models\service;
use App\Notifications\Gestapp;
use Attribute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
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
        $services=service::all();
        $roles=role::where('id', '!=' ,'1')->get();
        return view('auth.register', compact('services','roles'));
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
        'firstname' => 'required|string',
        'lastname' => 'required|string',
        'email' => 'required|email|unique:users',
        'birthday' => 'required',
        'role_id' => 'required|string',
        'sub' => 'required|string',
        'tel' => 'required|string',
      ]);

        $password= substr(str_shuffle(Hash::make(Str::random(8))) , 0 , 15);

        $user=User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'birthday' => $request->birthday,
            'role_id' => $request->role_id,
            'sub' => $request->sub,
            'password'=> Hash::make($password),
            'email' => $request->email,
            'tel' => $request->tel,
        ]);
        $user->notify(new Gestapp($user));
        return back()->with('message', 'Enregistrement effectué avec succès!');
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
