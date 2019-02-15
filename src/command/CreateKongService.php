<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Metawesome\KongPublish\Services\CurlService;

class CreateKongService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'KongService:up';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish microservice in Kong Api Gateway';

    /**
     * The Kong service api
     *
     * @var array
     */
    private $kongService = 'services';

    /**
     * The Kong routes api
     *
     * @var array
     */
    private $kongRoutes = 'routes';


    /**
     * The service data to register
     *
     * @var array
     */
    private $serviceData = [];

    /**
     * The service ID registered
     *
     * @var string
     */
    private $serviceId = null;

    /**
     * Default methods on routes
     *
     * @var array
     */
    private $defaultMethods = ['OPTIONS', 'GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->createService();
    }

    private function createService()
    {
        if (!$this->checkServiceConfig()) {
            return false;
        }

        $this->serviceData = $this->getServiceData();

        if (!$this->serviceData) {
            return false;
        }

        $post = $this->sendServiceData();

        if(empty($post['id'])) {
            return false;
        }

        $this->serviceId = $service['id'];

        return $this->createRoutes();
    }

    private function getServiceData()
    {
        return [
            'name'         => config('kong.service_name'),
            'protocol'     => config('kong.service_protocol'),
            'host'         => config('kong.service_host'),
            'port'         => config('kong.service_port'),
            'path'         => config('kong.service_path') ?? '/',
        ];
    }

    private function checkServiceConfig()
    {
        if (empty(config('kong.kong_api_url'))) {
            return false;
        }
        if (empty(config('kong.service_name'))) {
            return false;
        }
        if (empty(config('kong.service_host'))) {
            return false;
        }
        if (empty(config('kong.service_port'))) {
            return false;
        }

        return true;
    }

    private function sendServiceData()
    {
        $request = new CurlService();

        $request->to($this->getKongServiceRegisterApi())
             ->withData($this->serviceData)
             ->put();
        
        return $request;
    }

    private function createRoutes()
    {
        $routes = $this->getRoutes();

        $registeredRoutes = [];
        foreach($routes as $route) {
            $data = $this->getRouteData($route);

            if(!$registeredRoutes[] = $this->sendRouteData($data)) {
                return false;
            }
        }

        return true;
    }

    private function sendRouteData($data)
    {
        $request = new CurlService();

        $request->to($this->getKongServiceRegisterApi())
             ->withJsonData($this->serviceData)
             ->put();
        
        return $request;
    }

    private function getRouteData($route)
    {
        $service = new \stdClass;

        $service->id = $this->serviceId;

        return [
            'name'    => $this->configRouteName($route),
            'hosts'   => $this->getHosts(),
            'methods' => $this->getMethods(),
            'paths'   => [$this->formatRoute($route)],
            'strip_path' => config('kong.route_strip_path') ?? false,
            'service' => $service,
        ];
    }

    private function configRouteName($route)
    {
        return str_replace(['/'], '-', $route);
    }

    private function formatRoute($route)
    {
        if(strpos($route, '/') != 0) {
            $route = '/'.$route;
        }

        return $route;
    }

    private function getRoutesName()
    {
        if(!empty(config('kong.route_name'))) {
            return config('kong.route_name');
        }
    }

    private function getHosts()
    {
        if(!empty(config('kong.route_hosts'))) {
            return config('kong.route_hosts');
        }

        return ['localhost'];
    }

    private function getRoutes()
    {
        if (!empty(config('kong.route_paths'))) {
            return config('kong.route_paths');
        }

        return collect(\Route::getRoutes())->map(function ($route) { return $route->uri(); });
    }

    private function getMethods()
    {
        if (!empty(config('kong.methods')))
        {
            return config('kong.methods');
        }

        return $this->defaultMethods;
    }

    private function getKongServiceUrl()
    {
        return config('kong.kong_api_url');
    }

    private function getKongServiceRegisterApi()
    {
        return $this->getKongServiceUrl()."/".$this->kongService."/".config('kong.service_name');
    }

    private function getKongRouteRegisterApi()
    {
        return $this->getKongServiceUrl()."/".$this->kongRoutes;
    }
}
