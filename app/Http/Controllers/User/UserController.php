<?php

namespace App\Http\Controllers\User;

use App\Helpers\RequestHelpers;
use App\Helpers\ResponseHelper;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    private $request;

    /**
     * UserController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function create()
    {
        if ($errorMessage = RequestHelpers::checkNeededParams($this->request,
            ['username', 'password'])
        ) {
            return ResponseHelper::makeResponse(['errorMessage' => $errorMessage]);
        }

        $email = $this->request->has('email') ? $this->request->get('email') : '';
        $isAdmin = $this->request->has('is_admin') ? (int)$this->request->get('is_admin') : 0;
        $myAdmin = $this->request->has('belongs_to_admin') ? (int)$this->request->get('belongs_to_admin') : null;

        $newUser = User::create([
            'username' => $this->request->get('username'),
            'password' => bcrypt($this->request->get('password')),
            'email'    => $email,
            'is_admin' => $isAdmin,
            'my_admin' => $myAdmin,
        ]);

        return ResponseHelper::makeResponse([
            'data' => [
                'user_id'  => $newUser->id,
                'username' => $newUser->username,
                'email'    => $newUser->email,
                'is_admin' => $newUser->isAdmin(),
                'my_admin' => $newUser->myAdmin ? [
                    'admin_username' => $newUser->myAdmin->username,
                    'admin_id'       => $newUser->myAdmin->id,
                ] : null,
            ],
        ]);
    }
}
