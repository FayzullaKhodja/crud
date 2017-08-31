<?php

namespace Khodja\Crud\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class MakeCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud {name} {--route=web}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new crud files';

    /**
     * The absolute path of the vendor folder.
     *
     * @var string
     */
    private $vendor_path;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->vendor_path = realpath(__DIR__.'/../vendor');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = strtolower($this->argument('name'));
        $route = $this->option('route');
        
        $this->makeRoutes($name, $route);
        $this->makeController($name);
        $this->makeModel($name);
        $this->makeMigration($name);
        $this->makeViews($name);
    }

    /**
     * Routes for CRUD
     * 
     * @param  string $name
     * @param  string $route
     * @return void
     */
    public function makeRoutes($name, $route = 'web')
    {
        $routes = $this->vendor_path.'/routes.php';
        $crud_routes = file_get_contents($routes);

        $main_routes = base_path('routes/'.$route.'.php');
        $routes_content = file_get_contents($main_routes);
        
        $ucf_name = ucfirst($name);
        $controller_name = $ucf_name.'Controller';

        if (!preg_match('/'.$controller_name.'/i', $routes_content)) {

            $crud_routes = preg_replace('/\{ucf_name\}/i', $ucf_name, $crud_routes);
            $crud_routes = preg_replace('/\{name\}/i', $name, $crud_routes);
            $crud_routes = preg_replace('/\{Controller\}/i', $controller_name, $crud_routes);
            $routes_content .= "\n".$crud_routes."\n";

            if (file_put_contents($main_routes, $routes_content)) {
                $this->line('- '.$name.'[routes]: installed successfully');
            } else {
                $this->error('- '.$name.'[routes]: something went wrong!');
            }
        } else {
            $this->line('- '.$name.'[routes]: already installed');
        }
    }

    /**
     * Make Controller for CRUD
     * 
     * @param  string $name
     * @return void
     */
    public function makeController($name)
    {
        $ucf_name = ucfirst($name);
        $controller_name = $ucf_name.'Controller';

        $source_loc = $this->vendor_path.'/Controller.php';
        $dest_loc = app_path('Http/Controllers/Backend/'.$controller_name.'.php');

        if (!file_exists($dest_loc)) {
            $new_dir = dirname($dest_loc);
            // If the directory does not exist, we create a new one
            if (!is_dir($new_dir)) {
                mkdir($new_dir, 0755, true);
            }
            $cnt = file_get_contents($source_loc);
            $cnt = preg_replace('/\{name\}/i', $name, $cnt);
            $cnt = preg_replace('/\{Model\}/i', $ucf_name, $cnt);
            $cnt = preg_replace('/\{Controller\}/i', $controller_name, $cnt);

            if (file_put_contents($dest_loc, $cnt)) {
                $this->line('- '.$name.'[controller]: installed successfully');
            } else {
                $this->error('- '.$name.'[controller]: something went wrong!');
            }
        } else {
            $this->line('- '.$name.'[controller]: already installed');
        }
    }

    /**
     * Make Model for CRUD
     * 
     * @param  string $module
     * @return void
     */
    public function makeModel($name)
    {
        $ucf_name = ucfirst($name);

        $source_loc = $this->vendor_path.'/Model.php';
        $dest_loc = app_path('Http/Controllers/Models/'.$ucf_name.'.php');

        if (!file_exists($dest_loc)) {
            $new_dir = dirname($dest_loc);
            // If the directory does not exist, we create a new one
            if (!is_dir($new_dir)) {
                mkdir($new_dir, 0755, true);
            }
            $cnt = file_get_contents($source_loc);
            $cnt = preg_replace('/\{Model\}/i', $ucf_name, $cnt);

            if (file_put_contents($dest_loc, $cnt)) {
                $this->line('- '.$name.'[model]: installed successfully');
            } else {
                $this->error('- '.$name.'[model]: something went wrong!');
            }
        } else {
            $this->line('- '.$name.'[model]: already installed');
        }
    }

    /**
     * Make view for CRUD
     * 
     * @param  string $name
     * @return void
     */
    public function makeViews($name)
    {
        $ucf_name = ucfirst($name);

        $source_loc = $this->vendor_path.'/view.blade.php';
        $dest_loc = resource_path('views/backend/'.$name.'/index.blade.php');

        if (!file_exists($dest_loc)) {
            $new_dir = dirname($dest_loc);
            // If the directory does not exist, we create a new one
            if (!is_dir($new_dir)) {
                mkdir($new_dir, 0755, true);
            }
            $cnt = file_get_contents($source_loc);
            $cnt = preg_replace('/\{name\}/i', $ucf_name, $cnt);
            $cnt = preg_replace('/\{Controller\}/i', $ucf_name.'Controller', $cnt);

            if (file_put_contents($dest_loc, $cnt)) {
                $this->line('- '.$name.'[views]: installed successfully');
            } else {
                $this->error('- '.$name.'[views]: something went wrong!');
            }
        } else {
            $this->line('- '.$name.'[views]: already installed');
        }
    }

    /**
     * Make migration for CRUD
     * 
     * @param  string $module
     * @return void
     */
    public function makeMigration($name)
    {
        $d = Carbon::now();
        $migration = 'create_'.str_plural($name).'_table';

        $source_loc = $this->vendor_path.'/migration.php';
        $dest_loc = database_path('migrations/'.$d->format('Y_m_d_Hmi').'_'.$migration.'.php');

        if (!glob(database_path('migrations/*_'.$migration.'.php'))) {
            $cnt = file_get_contents($source_loc);
            $cnt = preg_replace('/\{migration\}/i', ucfirst(camel_case($migration)), $cnt);
            $cnt = preg_replace('/\{name\}/i', str_plural($name), $cnt);

            if (file_put_contents($dest_loc, $cnt)) {
                $this->line('- '.$name.'[migration]: installed successfully');
            } else {
                $this->error('- '.$name.'[migration]: something went wrong!');
            }
        } else {
            $this->line('- '.$name.'[migration]: already installed');
        }
    }
}
