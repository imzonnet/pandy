<?php namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;

class APIController extends Controller {

    public $statusCode = 200;

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     *
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function respondNotFound($message) {
        return $this->setStatusCode(404)->respondWithError($message);
    }

    public function respond($data, $headers = []) {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    public function respondWithError($message)
    {
        return $this->respond([
            'error' => [
                'message' => $message,
                'status_code' => $this->getStatusCode()
            ]
        ]);
    }
    public function respondWithSuccess($message)
    {
        return $this->respond([
            'status_code' => $this->getStatusCode(),
            'message' => $message
        ]);
    }

}