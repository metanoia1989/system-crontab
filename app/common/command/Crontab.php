<?php
declare (strict_types=1);

namespace app\common\command;

use app\admin\service\SystemCrontabService;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use utils\MyToolkit;


class Crontab extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('crontab')
            ->addOption('m', null, Option::VALUE_OPTIONAL, 'run mode')
            ->addOption('d', null, Option::VALUE_OPTIONAL, 'run daemon')
            ->setDescription('the crontab command');
    }

    protected function execute(Input $input, Output $output)
    {
        if (MyToolkit::functionDisabled('exec')) {
            $output->writeln('exec函数被禁用！');

            return;
        }
        $output->writeln(PHP_EOL . '======================== 启动系统任务 ========================' . PHP_EOL);

        $debug = $input->getOption('m') === 'debug' ? true : false;
        $daemon = $input->hasOption('d') ? true : false;
        $runtimePath = runtime_path();
        $systemCrontabServiceObj = new SystemCrontabService();
        $systemCrontabServiceObj->setDebug($debug)
            ->setDaemon($daemon)
            ->setName('System Crontab')
            ->setLogFile($runtimePath . DIRECTORY_SEPARATOR . 'logFile.log')
            ->setStdoutFile($runtimePath . DIRECTORY_SEPARATOR . 'log/stdoutFile.log')
            ->run();
    }
}
