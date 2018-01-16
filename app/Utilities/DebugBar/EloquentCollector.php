<?php

namespace App\Utilities\Debugbar;

use DebugBar\DataCollector\PDO\PDOCollector;
use DebugBar\DataCollector\PDO\TraceablePDO;
use Illuminate\Database\Capsule\Manager as Capsule;

class EloquentCollector extends PDOCollector
{
    public function __construct()
    {
        parent::__construct();
        $this->addConnection($this->getTraceablePdo(), "Eloquent PDO");
    }

    protected function getEloquentCapsule()
    {
        global $container;
        return $container->db;
    }

    protected function getEloquentPdo()
    {
        return $this->getEloquentCapsule()->getConnection()->getPdo();
    }

    protected function getTraceablePdo()
    {
        return new TraceablePDO($this->getEloquentPdo());
    }

    public function getName()
    {
        return "eloquent_pdo";
    }

    public function getWidgets()
    {
        return [
            "eloquent" => [
                'widget' => "PhpDebugBar.Widgets.SQLQueriesWidget",
                'map' => $this->getName(),
                'default' => "[]"
            ],
            "eloquent:badge" => [
                'map' => "eloquent_pdo.nb_statements",
                'default' => 0
            ]
        ];
    }
}