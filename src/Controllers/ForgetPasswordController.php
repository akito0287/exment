<?php

namespace Exceedone\Exment\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Exceedone\Exment\Model\Define;
use Exceedone\Exment\Model\LoginUser;
use Password;

class ForgetPasswordController extends Controller
{
    use SendsPasswordResetEmails;
    use \Exceedone\Exment\Controllers\AuthTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // add for exment_admins
        if (!Config::has('auth.passwords.exment_admins')) {
            Config::set('auth.passwords.exment_admins', [
                'provider' => 'exment-auth',
                'table' => 'password_resets',
                'expire' => 720,
            ]);
        }
        //TODO:only set admin::guest
        //$this->middleware('guest');
    }

    /**
     * Display the form to request a password reset link.
     * *Cutomize
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('exment::auth.email', $this->getLoginPageData());
    }
    
    //defining which password broker to use, in our case its the exment
    protected function broker()
    {
        return Password::broker('exment_admins');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $broker = $this->broker();
        $array = [
            //getColumnNameByTable(Define::SYSTEM_TABLE_NAME_USER, 'email') => $request->input('email')
            'email' => $request->input('email')
        ];
        $response = $broker->sendResetLink($array);

        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }
}
