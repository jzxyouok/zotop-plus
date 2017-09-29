<?php

namespace Modules\Core\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RebootCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'reboot
                            {--force : Force reboot app.}';



    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reboot the app';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 如果是本地或者测试模式或者处于debug状态下，不缓存路由和配置
        if ($this->laravel->environment('local','testing') OR $this->laravel['config']->get('app.debug')) {

            // 清除配置缓存
            $this->call('config:clear');
            
            // 清除路由缓存
            $this->call('route:clear');      

        } else {
            
            // 重建配置缓存
            $this->call('config:cache');

            // 重建路由缓存
            $this->call('route:cache');

        }        

        // 强制模式，刷新缓存
        if ($this->option('force')) {

            $this->call('clear-compiled');
            
            $this->call('cache:clear');

        }

        $this->info("Reboot : success");      
    }


}
