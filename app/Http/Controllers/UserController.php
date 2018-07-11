<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use FrameworkCore\BaseController;
use FrameworkCore\Utilities\DataTable;
use Illuminate\Database\Capsule\Manager as DB;

class UserController extends BaseController
{
    /**
     * [index description]
     * @param  Psr\Http\Message\RequestInterface $request
     * @param  Psr\Http\Message\ResponseInterface $response
     * @return mixed
     */
    public function index($request, $response)
    {
        return $this->view->render($response, "user/index.twig");
    }

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
     * @param  Psr\Http\Message\RequestInterface $request
     * @param  Psr\Http\Message\ResponseInterface $response
     * @return mixed
     */
    public function show($id, $request, $response)
    {
        $user = User::find($id);
        return $this->view->render($response, "user/show.twig", compact('user'));
    }

    /**
     * [create description]
     * @param  Psr\Http\Message\RequestInterface $request
     * @param  Psr\Http\Message\ResponseInterface $response
     * @return mixed
     */
    public function create($request, $response)
    {
        return $this->view->render($response, "user/create.twig");
    }

    /**
     * [store description]
     * @param  Psr\Http\Message\RequestInterface $request
     * @param  Psr\Http\Message\ResponseInterface $response
     * @return mixed
     */
    public function store($request, $response)
    {
        if (!(new UserRequest($request))->isValid())
        {
            return $response->withRedirect($this->router->pathFor('user.create'));
        }

        $input = $request->getParams();

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
     * @param  Psr\Http\Message\RequestInterface $request
     * @param  Psr\Http\Message\ResponseInterface $response
     * @return mixed
     */
    public function edit($id, $request, $response)
    {
        $user = User::find($id);
        return $this->view->render($response, "user/edit.twig", compact('user'));
    }

    /**
     * [update description]
     * @param  Psr\Http\Message\RequestInterface $request
     * @param  Psr\Http\Message\ResponseInterface $response
     * @return mixed
     */
    public function update($id, $request, $response)
    {
        if (!(new UserRequest($request))->isValid())
        {
            return $response->withRedirect($this->router->pathFor('user.edit', compact('id')));
        }

        $has_changed = User::_update($id, $request->getParams());

        flash($has_changed,
            ['success' => "Successfully updated"],
            ['warning' => "No changes"]
        );

        return $response->withRedirect($this->router->pathFor('user.list'));
    }

    /**
     * [delete description]
     * @param  Psr\Http\Message\RequestInterface $request
     * @param  Psr\Http\Message\ResponseInterface $response
     * @param  array $args
     * @return mixed
     */
    public function delete($id, $request, $response)
    {
        flash(User::destroy($id),
            ['success' => "Successfully deleted"],
            ['danger' => "Cannot delete the user this time."]
        );

        return $response->withRedirect($this->router->pathFor('user.list'));
    }
}
