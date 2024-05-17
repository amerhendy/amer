<?php
namespace Amerhendy\Amer;
//composer update
//composer dump-autoload
//php artisan vendor:publish
use Illuminate\Support\Facades\Config;
use Amerhendy\Amer\App\Helpers\AmerHelper;
use Amerhendy\Amer\App\Helpers\Library\AmerPanel\AmerPanel;
use Amerhendy\Amer\App\Helpers\Library\AmerPanel\AmerPanelFacade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;  
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class AmerServiceProvider extends ServiceProvider
{
    use \Amerhendy\Amer\App\Helpers\Library\Database\PublishesMigrations;
    public $startcomm="Amer";
    protected $defer = false;
    public $pachaPath="Amerhendy\Amer\\";
    public $customRoutesFilePath = ['AmerRoute.php','api.php'];
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->loadhelper();
        $this->app->singleton('alerts', function ($app) {
            return new AlertsMessageBag($app['session.store'], $app['config']);
        });
        $this->app->scoped('Amer', function ($app) {
            return new AmerPanel();
        });
        $this->app->singleton('AmerNamespaces', function ($app) {
            return new ViewNamespaces();
        });
        $this->app->singleton('assets', function ($app) {
            return new AssetManager();
        });
        $this->app->singleton('widgets', function ($app) {
            return new Collection();
        });
        date_default_timezone_set(Config('Amer.amer.timeZone') ?? 'Africa/Cairo');
    }

    /**
     * Bootstrap services.
     */
    public function boot(Router $router): void
    {
        $this->loadConfigs();
        $this->loadviewfiles();
        $this->loadTranslationsFrom(Config('Amer.amer.package_path')."lang", 'AMER');
        $this->setDisks();
        $this->registerMigrations(__DIR__.'/database/migrations');
        $this->publishFiles();
        $this->loadroutes($this->app->router);
        
    }
    function loadviewfiles() {
        $basefiles="views/Amer";
        if (file_exists($basefiles)) {
            $this->loadViewsFrom($basefiles, 'Amer');
        }
        $this->loadViewsFrom(Config('Amer.amer.package_path').'resources/views/Amer', 'Amer');
    }
    public function loadConfigs(){
        foreach(getallfiles(__DIR__.'/config') as $file){
            $this->mergeConfigFrom($file,Str::replace('/','.',Str::afterLast(Str::remove('.php',$file),'config/')));
        }
    }
    function setDisks(){
        //set root disk
        app()->config['filesystems.disks.root'] = [
            'driver'=>'local',
            'root'   => base_path(),
        ];
        //set public disk
        app()->config['filesystems.disks.'.config('Amer.amer.root_disk_name')] = [
            'driver'=>'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage/Amer/',
            'visibility' => 'public',
        ];
        //set private disk
        app()->config['filesystems.disks.'.config('Amer.amer.root_disk_name')] = [
            'driver'=>'local',
            'root' => storage_path('app/members'),
            'url' => env('APP_URL').'/storage/Amer/amer/',
            'visibility' => 'public',
        ];
    }
    /**
     * loadroutes
     *
     * @param Router $router
     * 
     * @return [type]
     */
    public function loadroutes(Router $router)
    {
        $routepath=getallfiles(config('Amer.amer.package_path').'route/');
        foreach($routepath as $path){
            $this->loadRoutesFrom($path);
        }
    }
    function publishFiles()  {
        $this->app->bind('path.public',function(){
           return config('Amer.amer.public_path'); 
        });
        $pb=config('Amer.amer.package_path') ?? __DIR__;
        $route = [$pb.'/route/Amer/' => base_path('Routes/Amer')];
        $error_views = [$pb.'/resources/views/Amer/Base/Errors/' => resource_path('views/errors')];
        $public_assets = [$pb.'/public' => config('Amer.amer.public_path')];
        $config_files = [$pb.'/config' => config_path()];
        $sidebar=[$pb.'/resources/views/Amer/Base/inc/menu/' => resource_path('views/vendor/Amer/Base/inc/menu')];
        $this->publishes($config_files, $this->startcomm.':config');
        $this->publishes($error_views, $this->startcomm.':errors');
        $this->publishes($sidebar, $this->startcomm.':sidebar');
        $this->publishes($public_assets, $this->startcomm.':public');
        $this->publishes($route, $this->startcomm.':routes');
    }
    public function loadhelper(){
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('AmerHelper',App\Helpers\AmerHelper::class);
        $loader->alias('TCPDF',App\Helpers\pdf\TCPDF::class);
        $loader->alias('Alert',App\Helpers\Alert::class);
        $loader->alias('Amer',Amerhendy\Amer\App\Helpers\Library\AmerPanel\AmerPanel::class);
        require_once __DIR__.'/macro.php';   
        Config('locale','ar');
        Config('fallback_locale','ar');
    }
    
    public function provides()
    {
        return ['AmerNamespaces','assets','alerts','widgets','Amer'];
    }
}
