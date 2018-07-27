<?php

namespace PageBlocker;

use PageBlocker\PageBlockerDAO;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Middleware
{
    protected $settings;

    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    /**
     * PageBlocker middleware
     *
     * @param  Request $request
     * @param  Response $response
     * @param  callable $next
     * @return callable
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $url = $request->getUri()->getPath();

        try {
            if (!isset($this->settings['db-config'])) throw new \Exception("PageBlocker Error: db-config key is missing.", 1);
            if (!isset($this->settings['table'])) throw new \Exception("PageBlocker Error: table key is missing.", 1);
            if (!isset($this->settings['glob-url'])) throw new \Exception("PageBlocker Error: glob-url key is missing.", 1);
            if (!isset($this->settings['unauthorizedCallback'])) throw new \Exception("PageBlocker Error: unauthorizedCallback key is missing.", 1);

            $pageBlocker = new PageBlockerDAO($this->settings['db-config'], $this->settings['table']);

            // throw error if not connected in database.
            if ($pageBlocker->getDB()->connect_errno !== 0) throw new \Exception($pageBlocker->getDB()->connect_error, 1);

            // create table for page blocker if not yet existing
            $pageBlocker->createTableIfNotExist($this->settings['table']);

            if (strpos($url, $this->settings['glob-url']) === 0)
            {
                if ($pageBlocker->isAuthorize())
                {
                    $pageBlocker->add();
                }
                else
                {
                    $this->settings['unauthorizedCallback']();
                }
            }
        } catch (\Exception $e) {
            exit("<pre>".$e);
        }

        return $next($request, $response);
    }
}
