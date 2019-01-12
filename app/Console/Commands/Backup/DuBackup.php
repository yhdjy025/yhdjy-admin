<?php
/**
 * Created by PhpStorm.
 * User: yhdjy
 * Date: 2019-01-06
 * Time: 14:33
 */

namespace App\Console\Commands\Backup;


use App\Tools\DBManage;
use Illuminate\Console\Command;

class DuBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     * @translator laravelacademy.org
     */
    protected $signature = 'backup:du {--ip=} {--in}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sql back up';

    /**
     * The drip e-mail service.
     *
     * @var DripEmailer
     */

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
        $ip = $this->option('ip');
        $in = $this->option('in');
        if (!empty($ip)) {
            $con = [
                'port' => '3306',
                'username' => 'username',
                'password' => 'password',
                'database' => 'database'
            ];
            $basepath = storage_path('backup');
            $db = new DBManage ($ip, $con['username'], $con['password'], $con['database'], 'utf8');
            if (empty($in)) {
                $dir = $basepath.'/'.$ip.'/';
                // 参数：备份哪个表(可选),备份目录(可选，默认为backup),分卷大小(可选,默认2000，即2M)
                $db->backup('', $dir, 20000);
            } else {
                $dirs = scandir($basepath);
                foreach ($dirs as $dir) {
                    if ($dir != $ip && $dir != '.' && $dir != '..') {
                        $files = scandir($basepath.'/'.$dir);
                        rsort($files);
                        $selectedFile = $basepath.'/'.$dir.'/'.current($files);
                        $db->restore($selectedFile);
                    }
                }
            }
        }
    }
}