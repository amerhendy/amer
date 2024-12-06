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
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class AmerServiceProvider extends ServiceProvider
{
    use \Amerhendy\Amer\App\Helpers\Library\Database\PublishesMigrations;
    public $startcomm="Amer";
    protected $defer = false;
    public static $pachaPath="Amerhendy\Amer\\";
    public static $config;
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
        date_default_timezone_set(Config('Amer.Amer.timeZone') ?? 'Africa/Cairo');
    }

    /**
     * Bootstrap services.
     */
    public function boot(Router $router): void
    {
        $this->loadConfigs();
        self::$config=Config('Amer');
        if(Config('Amer.Amer.package_path')){
            self::$pachaPath=cleanDir(Config('Amer.Amer.package_path'));
        }else{
            self::$pachaPath=cleanDir(__DIR__);
        }
        $this->loadViewsFrom(cleanDir([self::$pachaPath,'resources/views/Amer']), 'Amer');
        $this->loadTranslationsFrom(cleanDir([self::$pachaPath,"lang"]), 'AMER');
        $this->registerMigrations(cleanDir([self::$pachaPath,"database",'migrations']));
        $this->loadroutes($this->app->router);
        $this->setDisks();
        $this->publishFiles();

    }
    public function loadConfigs(){
        foreach(getallfiles(__DIR__.'/config') as $file){
            if(!Str::contains($file, 'config'.DIRECTORY_SEPARATOR."Amer".DIRECTORY_SEPARATOR)){
                $name=Str::afterLast(Str::remove('.php',$file),'config'.DIRECTORY_SEPARATOR);
            }else{
                $name='Amer.'.ucfirst(Str::afterLast(Str::remove('.php',$file),'config'.DIRECTORY_SEPARATOR."Amer".DIRECTORY_SEPARATOR));
            }

            $this->mergeConfigFrom(
                $file,$name
            );
        }
    }

    public function loadroutes(Router $router)
    {
        $routepath=getallfiles(cleanDir([self::$pachaPath,'route']));
        foreach($routepath as $path){
            if(!\Str::contains($path, 'api.php')){
                $this->loadRoutesFrom($path);
            }else{
                Route::group($this->apirouteConfiguration(), function () use($path){
                    $this->loadRoutesFrom($path);
                });
            }
        }
    }
    protected function apirouteConfiguration()
    {
        return [
            'prefix' =>'api/'.config('Amer.Amer.api_version')??'v1',
            'middleware' => 'client',
            'name'=>(config('Amer.Amer.routeName_prefix') ?? 'amer').'Api',
            'namespace'  =>config('Amer.Amer.Controllers','\\Amerhendy\Amer\App\Http\Controllers\\'),
        ];
    }
    function setDisks(){
        //set root disk
        app()->config['filesystems.disks.root'] = [
            'driver'=>'local',
            'root'   => base_path(),
        ];
        //set public disk
        app()->config['filesystems.disks.'.config('Amer.Amer.root_disk_name')] = [
            'driver'=>'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage/Amer/',
            'visibility' => 'public',
        ];
        //set private disk
        app()->config['filesystems.disks.'.config('Amer.Amer.root_disk_name')] = [
            'driver'=>'local',
            'root' => storage_path('app/members'),
            'url' => env('APP_URL').'/storage/Amer/amer/',
            'visibility' => 'public',
        ];
    }
    function publishFiles()  {
        $this->app->bind('path.public',function(){
            return realpath(config('Amer.Amer.public_path'));
         });
        $error_views = [cleanDir([self::$pachaPath,'/resources/views/Amer/Base/Errors/']) => resource_path('views/errors')];
        $public_assets = [cleanDir([self::$pachaPath,'public']) => config('Amer.Amer.public_path')];
        $this->publishes($error_views, $this->startcomm.':errors');
        $this->publishes($public_assets, $this->startcomm.':public');
    }
    public function loadhelper(){
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('AmerHelper',App\Helpers\AmerHelper::class);
        $loader->alias('TCPDF',App\Helpers\pdf\TCPDF::class);
        $loader->alias('Alert',App\Helpers\Alert::class);
        $loader->alias('Amer',Amerhendy\Amer\App\Helpers\Library\AmerPanel\AmerPanel::class);
        require_once __DIR__.'/macro.php';
        $lng=\Str::before(config('Amer.Amer.lang'),'-');
        Config('locale',$lng);
        Config('fallback_locale',$lng);
    }
    public function provides()
    {
        return ['AmerNamespaces','assets','alerts','widgets','Amer'];
    }
}
