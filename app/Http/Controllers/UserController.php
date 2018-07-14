<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use SlimRodrigoCore\BaseController;
use SlimRodrigoCore\Utilities\DataTable;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class UserController extends BaseController
{
    /**
     * Display list of users.
     *
     * @param  Response $response
     * @return Response
     */
    public function index(Response $response)
    {
        return $this->view->render($response, "user/index.twig");
    }

    /**
     * Fetch all users in database then return it as json.
     *
     * @param  Request $request
     * @param  Response $response
     * @return json
     */
    public function data(Request $request, Response $response)
    {
        $data = $request->getParams();
        $select = \DB::table('users');
        $columns = ['id', 'first_name', 'last_name', 'email'];

        $dataTable = new DataTable($data, $select, $columns);
        return $response->withJson($dataTable->getResponse());
    }

    /**
     * Display the user details.
     *
     * @param  integer $id
     * @param  Response $response
     * @return Response
     */
    public function show($id, Response $response)
    {
        $user = User::find($id);
        return $this->view->render($response, "user/show.twig", compact('user'));
    }

    /**
     * Display form to create user info.
     *
     * @param  Response $response
     * @return Response
     */
    public function create(Response $response)
    {
        return $this->view->render($response, "user/create.twig");
    }

    /**
     * After submitting the form, the user info should be save in the database.
     *
     * @param  UserRequest $_request
     * @param  Response $response
     * @return Response
     */
    public function store(UserRequest $_request, Response $response)
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
     * Display form to edit user info.
     *
     * @param  integer $id
     * @param  Response $response
     * @return Response
     */
    public function edit($id, Response $response)
    {
        $user = User::find($id);
        return $this->view->render($response, "user/edit.twig", compact('user'));
    }

    /**
     * After submitting the form, the user info should be updated in the database.
     *
     * @param  integer $id
     * @param  UserRequest $_request
     * @param  Response $response
     * @return Response
     */
    public function update($id, UserRequest $_request, Response $response)
    {
        $has_changed = User::_update($id, $_request->getParams());

        flash($has_changed,
            ['success' => "Successfully updated"],
            ['warning' => "No changes"]
        );

        return $response->withRedirect($this->router->pathFor('user.list'));
    }

    /**
     * Delete the specific user.
     *
     * @param  integer $id
     * @param  Response $response
     * @return Response
     */
    public function delete($id, Response $response)
    {
        flash(User::destroy($id),
            ['success' => "Successfully deleted"],
            ['danger' => "Cannot delete the user this time."]
        );

        return $response->withRedirect($this->router->pathFor('user.list'));
    }
}
