<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use FrameworkCore\BaseController;
use FrameworkCore\Utilities\DataTable;
use Illuminate\Database\Capsule\Manager as DB;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserController extends BaseController
{
    /**
     * [index description]
     * @param  ResponseInterface $response
     * @return ResponseInterface
     */
    public function index($response)
    {
        return $this->view->render($response, "user/index.twig");
    }

    /**
     * [data description]
     *
     * @param  ServerRequestInterface $request
     * @param  ResponseInterface $response
     * @return json
     */
    public function data($request, $response)
    {
        $data = $request->getParams();
        $select = DB::table('users');
        $columns = ['id', 'first_name', 'last_name', 'email'];

        $dataTable = new DataTable($data, $select, $columns);
        return $response->withJson($dataTable->getResponse());
    }

    /**
     * [show description]
     * @param  ResponseInterface $response
     * @return ResponseInterface
     */
    public function show($id, $request, $response)
    {
        $user = User::find($id);
        return $this->view->render($response, "user/show.twig", compact('user'));
    }

    /**
     * [create description]
     * @param  ResponseInterface $response
     * @return ResponseInterface
     */
    public function create($response)
    {
        return $this->view->render($response, "user/create.twig");
    }

    /**
     * [store description]
     * @param  UserRequest $UserRequest
     * @param  ResponseInterface $response
     * @return ResponseInterface
     */
    public function store(UserRequest $UserRequest, $response)
    {
        $input = $UserRequest->getParam('email');

        $result = User::create([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'email' => $input['email'],
        ]);

        flash($result instanceof User,
            ['success' => "Successfully Created User"],
            ['danger' => "Cannot create user this time."]
        );

        return $response->withRedirect($this->router->pathFor('user.list'));
    }

    /**
     * [edit description]
     * @param  ResponseInterface $response
     * @return ResponseInterface
     */
    public function edit($id, $response)
    {
        $user = User::find($id);
        return $this->view->render($response, "user/edit.twig", compact('user'));
    }

    /**
     * [update description]
     * @param  UserRequest $UserRequest
     * @param  ResponseInterface $response
     * @return mixed
     */
    public function update($id, UserRequest $UserRequest, $response)
    {
        $has_changed = User::_update($id, $UserRequest->getParams());

        flash($has_changed,
            ['success' => "Successfully updated"],
            ['warning' => "No changes"]
        );

        return $response->withRedirect($this->router->pathFor('user.list'));
    }

    /**
     * [delete description]
     * @param  ResponseInterface $response
     * @param  array $args
     * @return mixed
     */
    public function delete($id, $response)
    {
        flash(User::destroy($id),
            ['success' => "Successfully deleted"],
            ['danger' => "Cannot delete the user this time."]
        );

        return $response->withRedirect($this->router->pathFor('user.list'));
    }
}
