<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_id' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'date_embauche' => ['required', 'date', 'max:255'],
            'directions_id' => ['required', 'integer', 'max:255'],
            'filliale_id' => ['required', 'integer', 'max:255'],
            'isEmbauche' => ['required', 'string', 'max:255'],
            'jour_de_conger' => ['required', 'string', 'max:255'],
            'pays_id' => ['required', 'string', 'max:255'],
            'ville' => ['required', 'string', 'max:255'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),

            // Création du profil utilisateur
            UserProfile::create([
                'user_id' => $data.['id'],
                'phone_number' => $data['phone_number'],
                'date_embauche' => $data['date_embauche'],
                'directions_id' => $data['direction_id'],
                'filliale_id' => $data['filliale_id'],
                'isEmbauche'=> $data['isEmbauche'],
                'jour_de_conger' => 30,
                'pays_id' => 1,
                'ville' => "ABIDJAN",// Par défaut, on met que l'utilisateur est embauché
            ])
        ]);


    }
}
