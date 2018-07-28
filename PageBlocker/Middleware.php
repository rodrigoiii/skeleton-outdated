<?php

namespace PageBlocker;

use PageBlocker\PageBlockerDAO;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Middleware
{
    protected $glob_uri;
    protected $settings;
    protected $callback;

    public function __construct($glob_uri, $settings, $callback)
    {
        $this->glob_uri = $glob_uri;
        $this->settings = $settings;
        $this->callback = $callback;
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
        $silent_error = isset($this->settings['silent_error']) ? $this->settings['silent_error'] : false;
        $silent_callback = isset($this->callback['silent_callback']) ? $this->callback['silent_callback'] : function() { exit("PageBlocker not worked properly."); };
        $block_time = isset($this->settings['block_time']) ? $this->settings['block_time'] : 60 * 30;
        $attempt_length = isset($this->settings['attempt_length']) ? $this->settings['attempt_length'] : 5;

        try {
            if (!isset($this->settings['db_config'])) throw new \Exception("PageBlocker Error: db_config key is missing.", 1);
            if (!isset($this->settings['table'])) throw new \Exception("PageBlocker Error: table key is missing.", 1);
            if (!isset($this->callback['unauthorized_callback'])) throw new \Exception("PageBlocker Error: unauthorized_callback key is missing.", 1);

            $pageBlocker = new PageBlockerDAO($this->settings['db_config'], $this->settings['table'], $block_time, $attempt_length);

            // throw error if not connected in database.
            if ($pageBlocker->getDB()->connect_errno !== 0) throw new \Exception($pageBlocker->getDB()->connect_error, 1);

            // create table for page blocker if not yet existing
            $pageBlocker->createTableIfNotExist($this->settings['table']);

            if (strpos(Helper::getURI(), $this->glob_uri) === 0)
            {
                if (!$pageBlocker->isAuthorize())
                {
                    $this->callback['unauthorized_callback']();
                    exit;
                }

                $pageBlocker->add();
            }
        } catch (\Exception $e) {
            if (!$silent_error)
            {
                exit("<pre>".$e);
            }
            else
            {
                $silent_callback();
            }
        }

        return $next($request, $response);
    }
}
