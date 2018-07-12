<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use FrameworkCore\BaseController;
use FrameworkCore\Utilities\DataTable;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class UserController extends BaseController
{
    /**
     * [index description]
     *
     * @param  Response $response
     * @return Response
     */
    public function index($response)
    {
        return $this->view->render($response, "user/index.twig");
    }

    /**
     * [data description]
     *
     * @param  Request $request
     * @param  Response $response
     * @return json
     */
    public function data($request, $response)
    {
        $data = $request->getParams();
        $select = \DB::table('users');
        $columns = ['id', 'first_name', 'last_name', 'email'];

        $dataTable = new DataTable($data, $select, $columns);
        return $response->withJson($dataTable->getResponse());
    }

    /**
     * [show description]
     *
     * @param  integer $id
     * @param  Response $response
     * @return Response
     */
    public function show($id, $response)
    {
        $user = User::find($id);
        return $this->view->render($response, "user/show.twig", compact('user'));
    }

    /**
     * [create description]
     *
     * @param  Response $response
     * @return Response
     */
    public function create($response)
    {
        return $this->view->render($response, "user/create.twig");
    }

    /**
     * [store description]
     *
     * @param  UserRequest $_request
     * @param  Response $response
     * @return Response
     */
    public function store(UserRequest $_request, $response)
    {
        $input = $_request->getParams();

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
     *
     * @param  integer $id
     * @param  Response $response
     * @return Response
     */
    public function edit($id, $response)
    {
        $user = User::find($id);
        return $this->view->render($response, "user/edit.twig", compact('user'));
    }

    /**
     * [update description]
     *
     * @param  integer $id
     * @param  UserRequest $_request
     * @param  Response $response
     * @return Response
     */
    public function update($id, UserRequest $_request, $response)
    {
        $has_changed = User::_update($id, $_request->getParams());

        flash($has_changed,
            ['success' => "Successfully updated"],
            ['warning' => "No changes"]
        );

        return $response->withRedirect($this->router->pathFor('user.list'));
    }

    /**
     * [delete description]
     *
     * @param  integer $id
     * @param  Response $response
     * @return Response
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
