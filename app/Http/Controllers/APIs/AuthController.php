<?php namespace App\Http\Controllers\APIs;


use App\Http\Requests\InfoRequest;
use App\Http\Requests\RegisterRequest;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends APIController {

    protected $user;

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    public function postLogin(Request $request) {

        $v = Validator::make($request->all(), [
            'email'    => 'required|email',
        ]);

        if ($v->fails()) return $this->respondNotFound($v->errors()->first());

        if( $userID = $this->user->getUserIdByEmail($request->get('email')) ) {
            if ( Auth::loginUsingId($userID) ) {
                // Authentication passed...
                $user = Auth::guard()->user();
                $user->api_token = str_random(60);
                $user->save();
                return $this->respond([
                    'login_state' => 200,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'api_token' => $user->api_token
                ]);
            }
            return $this->respondNotFound('User Not Found!');
        }
        return $this->respondNotFound('Email Incorrect');

    }

    /**
     * Get User Info
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfo() {
        return $this->respond(Auth::guard('api')->user());
    }

    /**
     * Update user Information
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postUpdateInfo(Request $request)
    {
        $user = Auth::guard('api')->user();
        $this->user->update($user, $request->all());
        return $this->respondWithSuccess('The user information has been updated');
    }

    public function postRegisterUser(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email'    => 'required|email',
        ]);
        if ($v->fails()) return $this->respondNotFound($v->errors()->first());

        if( $userID = $this->user->getUserIdByEmail($request->get('email')) ) {
            if ( Auth::loginUsingId($userID) ) {
                // Authentication passed...
                $user = Auth::guard()->user();
                $user->api_token = str_random(60);
                $user->save();
                return $this->respondWithSuccess(Auth::guard()->user());
            }
            return $this->respondNotFound('User Not Found!');
        } else {
            $v = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required|unique:users,phone',
                'loan_term' => 'required',
                'interest_rate' => 'required',
            ]);
            if ($v->fails()) return $this->respondNotFound($v->errors()->first());

            $user = $this->user->create($request->all());
            if ( Auth::loginUsingId($user->id) ) {
                // Authentication passed...
                $user = Auth::guard()->user();
                $user->api_token = str_random(60);
                $user->loan_term = 30;
                $user->save();
                return $this->respondWithSuccess(Auth::guard()->user());
            }
        }
        return $this->respondWithSuccess('The user information has been created');
    }

    public function sendEmail(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email' => 'required|email',
            'phone' => 'required',
            'name' => 'required',
            'option' => 'required'
        ]);
        if ($v->fails()) return $this->respondNotFound($v->errors()->first());

        $user['email'] = $request->get('email');
        $user['name'] = $request->get('name');
        $user['phone'] = $request->get('phone');
        $user['option'] = $request->get('option');

        try {
            Mail::send('emails.contact', ['user' => $user], function ($m) use ($user) {
                $m->to(env('ADMIN_EMAIL', 'vnzacky39@gmail.com'))->subject('Pandy - Contact to discuss');
            });
            return $this->respondWithSuccess('The email has been sent');

        } catch(Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

}