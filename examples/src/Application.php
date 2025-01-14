<?php
/**
 * Created by PhpStorm.
 * User: 白猫
 * Date: 2019/5/7
 * Time: 10:44
 */

namespace ESD\Plugins\Tracing\Examples;

use ESD\Go\GoApplication;
use ESD\Plugins\DBTracing\DBTracingPlugin;
use ESD\Plugins\HttpClientTracing\HttpClientTracingPlugin;
use ESD\Plugins\MethodTracing\MethodTracingPlugin;
use ESD\Plugins\RequestTracing\RequestTracingPlugin;

class Application extends GoApplication
{
    public function configure()
    {
        $this->addPlug(new RequestTracingPlugin());
        $this->addPlug(new DBTracingPlugin());
        $this->addPlug(new MethodTracingPlugin());
        $this->addPlug(new HttpClientTracingPlugin());
        parent::configure();
    }
}