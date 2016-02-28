<?php namespace App\Http\Controllers\APIs;


use App\Http\Requests\InfoRequest;
use App\Http\Requests\RegisterRequest;
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
                    'login_state' => 200,
                    'name' => $user->name,
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
     * @param InfoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postUpdateInfo(InfoRequest $request)
    {
        $user = Auth::guard('api')->user();
        $this->user->update($user, $request->all());
        return $this->respondWithSuccess('The user information has been updated');
    }

    public function postRegisterUser(RegisterRequest $request)
    {
       $this->user->create($request->all());
        return $this->respondWithSuccess('The user information has been created');
    }

}