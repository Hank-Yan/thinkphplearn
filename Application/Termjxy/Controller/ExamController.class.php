<?php
/**
 * 新添加的文件，在控制器里面操作数据的时候都需要带前缀
 */

namespace Termjxy\Controller;

use Think\Controller;

class ExamController extends Controller
{
    /**
     * ExamController constructor.
     */
    public function __construct()
    {
        // 自己定义构造方法后，需要手动调用父类的构造方法
        parent::__construct();
        // 动态设定当前页面使用的数据库前缀
        C('DB_PREFIX', 'exam_');
    }

    public function index()
    {
        $m = M('category1');
        $result = $m->select();
        $this->assign('val', $result[0]['name']);
        $this->display();
    }

    public function test()
    {
        $value = I('test');
        // TP 里面使用 ajaxReturn 来进行元素的返回
        $this->ajaxReturn($value);
    }

}