<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\InscriptionMail;
use App\Mail\RecuperationMail;
use App\Mail\mail_demandes;
use App\Mail\mail_validates;
use App\Mail\mail_rejets;
use App\Mail\mail_rapport;
use App\Mail\mailuser;
use App\Mail\ConfirmInscriptionMail;
use App\Mail\mail_mission;
use Illuminate\Support\Facades\Mail;
use App\Traits\TracksUserActions;

class SendEmailController extends Controller
{
    use TracksUserActions;
    //dd($request);
    public function NotificationMail($request, $user)
    {

        // $moreUsers=array($request->emailMutuelle,'souscription@amgs.africa');
        // ->bcc($moreUsers)
        //
        // if (app()->environment('production'))
        // {
            $moreUsers=array('w09nenebi@gmail.com');
            Mail::to($user->email)
                ->cc($moreUsers)
                ->send(new mail_demandes($request, $user));
        // }
        // else
        // {
        //     Mail::to('w09nenebi@gmail.com')
        //         ->send(new mail_demandes($request, $user));
        // }

            // if (Mail::failures()) {
            //     return response()->json(['email'=>'Echec Désolé! reessayer plus tard']);
            // }else{
            //     return response()->json(['email' =>'Success! email bien envoyé']);
            //     }
    }

    public function NotificationMission($request, $user)
    {

        // $moreUsers=array($request->emailMutuelle,'souscription@amgs.africa');
        // ->bcc($moreUsers)
        //
        // if (app()->environment('production'))
        // {
            $moreUsers=array('w09nenebi@gmail.com');
            Mail::to($user->email)
                ->cc($moreUsers)
                ->send(new mail_mission($request, $user));
        // }
        // else
        // {
        //     Mail::to('w09nenebi@gmail.com')
        //         ->send(new mail_demandes($request, $user));
        // }

            // if (Mail::failures()) {
            //     return response()->json(['email'=>'Echec Désolé! reessayer plus tard']);
            // }else{
            //     return response()->json(['email' =>'Success! email bien envoyé']);
            //     }
    }

    public function NotificationValidate($request, $user)
    {

        // $moreUsers=array ('w09nenebi@gmail.com','samuel.blay@amgs.africa');
        // $moreUsers=array($request->emailMutuelle,'souscription@amgs.africa');
        // ->bcc($moreUsers)
        //->cc($moreUsers)
        // if (App::environment(['production']))
        // {
            Mail::to($user->email)
                ->send(new mail_validates($request, $user));
        // }
        // else
        // {
        //     Mail::to('w09nenebi@gmail.com')
        //         ->send(new mail_demandes($request, $user));
        // }

        // if (Mail::failures()) {
        //     return response()->json(['email'=>'Echec Désolé! reessayer plus tard']);
        // }else{
        //     return response()->json(['email' =>'Success! email bien envoyé']);
        //     }
    }

    public function NotificationRapport($demandes)
    {

        // $moreUsers=array ('w09nenebi@gmail.com','samuel.blay@amgs.africa');
        // $moreUsers=array($request->emailMutuelle,'souscription@amgs.africa');
        // ->bcc($moreUsers)
        //->cc($moreUsers)
        // if (App::environment(['production']))
        // {
            Mail::to($demandes)
                ->send(new mail_validates($demandes,$demandes->user));
        // }
        // else
        // {
        //     Mail::to('w09nenebi@gmail.com')
        //         ->send(new mail_demandes($demandes));
        // }

        // if (Mail::failures()) {
        //     return response()->json(['email'=>'Echec Désolé! reessayer plus tard']);
        // }else{
        //     return response()->json(['email' =>'Success! email bien envoyé']);
        //     }
    }

    public function NotificationRejet($users)
    {
            //  dd($user);
        // $moreUsers=array ('w09nenebi@gmail.com','samuel.blay@amgs.africa');
        // $moreUsers=array($request->emailMutuelle,'souscription@amgs.africa');
        // ->bcc($moreUsers)
        //->cc($moreUsers)
        // if (App::environment(['production']))
        // {
            Mail::to($users->email)
                ->send(new mail_rejets($users));
        // }
        // else
        // {
        //     Mail::to('w09nenebi@gmail.com')
        //         ->send(new mail_demandes($users));
        // }

        // if (Mail::failures()) {
        //     return response()->json(['email'=>'Echec Désolé! reessayer plus tard']);
        // }else{
        //     return response()->json(['email' =>'Success! email bien envoyé']);
        //     }
    }

    public function NotificationCreateuser($user, $defaultPassword)
    {
        $moreUsers=array('w09nenebi@gmail.com');
            Mail::to($user->email)
                ->cc($moreUsers)
                ->send(new mailuser($user, $defaultPassword));
        // $this->user = $user;
        // $this->defaultPassword = $defaultPassword;
    }

    // public function inscription($request)
    // {
    //     //dd($request);
    //     // $moreUsers=array ('samuel.blay@amgs.africa','js2sam@outlook.com');
    //     // $moreUsers=array($request->emailMutuelle,'souscription@amgs.africa');
    //     // ->bcc($moreUsers)
    //     //->cc($moreUsers)
    //     Mail::to($request->email)
    //         ->send(new InscriptionMail($request));

    //     if (Mail::failures()) {
    //         return response()->json(['email'=>'Echec Désolé! reessayer plus tard']);
    //     }else{
    //         return response()->json(['email' =>'Success! email bien envoyé']);
    //         }
    // }
    // public function confirmation($request,$typeResponse)
    // {
    //     //dd($request);
    //     // $moreUsers=array ('samuel.blay@amgs.africa','js2sam@outlook.com');
    //     // $moreUsers=array($request->emailMutuelle,'souscription@amgs.africa');
    //     // ->bcc($moreUsers)
    //     //->cc($moreUsers)
    //     Mail::to($request->email)
    //         ->send(new ConfirmInscriptionMail($request,$typeResponse));

    //     if (Mail::failures()) {
    //         return response()->json(['email'=>'Echec Désolé! reessayer plus tard']);
    //     }else{
    //         return response()->json(['email' =>'Success! email bien envoyé']);
    //         }
    // }
}
