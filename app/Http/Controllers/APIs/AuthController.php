<?php namespace App\Http\Controllers\APIs;


use App\Repositories\UserRepository;
use Illuminate\Http\Request;
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
                    'data' => $user
                ]);
            }
        }
        return $this->respondNotFound('Email Incorrect');

    }

    public function getUser() {
        return $this->respond([
            'data' => Auth::guard('api')->user()
        ]);
    }

}