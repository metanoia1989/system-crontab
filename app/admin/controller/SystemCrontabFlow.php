<?php


namespace app\admin\controller;


use app\admin\model\SystemCrontabFlow as SystemCrontabFlowModel;
use app\common\controller\AdminController;
use app\admin\traits\Curd;
use think\App;

class SystemCrontabFlow extends AdminController
{
    use Curd;

    protected $relationSearch = true;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new SystemCrontabFlowModel();
    }
}
