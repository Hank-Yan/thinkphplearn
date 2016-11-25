<?php
/**
 * 新添加的文件，在控制器里面操作数据的时候都需要带前缀
 */

namespace Termjxy\Controller;

use Think\Controller;

class StandardController extends Controller
{
    /**
     * ExamController constructor.
     */
    public function __construct()
    {
        // 自己定义构造方法后，需要手动调用父类的构造方法
        parent::__construct();
        // 动态设定当前页面使用的数据库前缀
        C('DB_PREFIX', 'standard_');
    }

    public function index()
    {
        $m = M('category1');
        $result = $m->select();
        $this->assign('val', $result[0]['name']);
        $this->display();
    }
}