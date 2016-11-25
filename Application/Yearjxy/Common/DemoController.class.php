<?php
/**
 * 数据库查询的其他一些方式：
 *     查询使用：
 *        $Model = new \Think\Model() // 实例化一个model对象 没有对应任何数据表
 *        $Model->query("select * from think_user where status=1");
 *     更新使用:
 *         $Model = new \Think\Model() // 实例化一个model对象 没有对应任何数据表
 *         $Model->execute("update think_user set name='thinkPHP' where status=1");
 *
 *
 */

namespace Home\Controller;

use Think\Controller;

/**
 * 供其他页面使用的Demo 类
 * Class DemoController
 * @package Termjxy\Controller
 */
class DemoController extends Controller
{

//    public function index() {
//        $m = M('Billinfo');
//        
//        //换页插件需要三个参数，1. 总数(用于返回我们的page对象和show 对象) 2. page 对象（控制每页显示第几条数据到第几条数据） 3. show 对象（控制前台页面显示分页插件）
//        $count = $m->count();//count 函数可以获取到数量
//        
//        $pageObj = setPage($count,5);//setPage 函数返回两个参数，一个是page 对象，另一个是 show 对象
//        $page = $pageObj['page'];
//        $show = $pageObj['show'];
//        $data = $m->order('id')->limit($page->firstRow.','.$page->listRows)->select();//limit 方法相当于第几页到第几页
//        
//         //下面循环用于组装$data 信息
//        foreach ($data as $key => $value){
//            $user = M('User');
//            $uid = $value['userid'];
//            $arr = $user->find($uid);
//            $name = $arr['username'];
//            $data[$key]['username'] = $name;
//        }
//        $this->assign('list',$data);//data 和 show 是一个统一的整体
//        $this->assign('page',$show);// 赋值分页输出,上面data给页面数据使用，show 给分页组件使用
//        
//        $this->display();
//    }


    public function index()
    {
        $m = M('Billinfo');
        $uid = session('id');
        $where['userid'] = $uid;
        $where['status'] = 0;//未通过审核
        //$data = $m->where($where)->select();

        $count = $m->where($where)->count();//链式操作每一步都是返回一个model 对象
        $pageObj = setPage($count, 5);
        $page = $pageObj['page'];
        $show = $pageObj['show'];
        $data = $m->order('id')->where($where)->limit($page->firstRow . ',' . $page->listRows)->order("id desc")->select();


        //下面循环查找用户姓名
//        foreach ($data as $key => $value){
//            $user = M('User');
//            $uid = $value['userid'];
//            $arr = $user->find($uid);
//            $name = $arr['username'];
//            $data[$key]['username'] = $name;
//        }


        //用于判断是否有数据
        if (!$data) {
            $empty = TRUE;
        } else {
            $empty = FALSE;
        }
        $this->assign("isEmpty", $empty);

//        dump($data);
        $this->assign('list', $data);
        $this->assign('page', $show);
        $this->display();
    }

    public function saveBill()
    {
        $info = I('billinfo');
        $count = I('count');
        //dump($info);//看能够收到

        //获取user id信息
        $userid = session('id');

        $m = M('Billinfo');
        $where['info'] = $info;
        $where['count'] = $count;
        $where['userid'] = $userid;
        $lastId = $m->add($where);
        if ($lastId) {
            $this->success("保存成功");
        } else {
            $this->error("保存失败");
        }
    }

    public function edit()
    {
        $id = I('id');
        $m = M('Billinfo');
        $where['id'] = $id;
        $data = $m->where($where)->find();
        $this->assign('count', $data['count']);
        $this->assign('info', $data['info']);
        $this->assign('id', $id);
        $this->display();
    }


    public function delete()
    {
        $m = M('Billinfo');
        $id = I('id');//获取需要删除的数据的id
        $where['id'] = $id;
        $num = $m->where($where)->delete();

        if ($num > 0) {
            $this->success("删除成功");
        } else {
            $this->error("删除失败");
        }

        //下面是ajax 返回时候的写法,ajax 的写法还是像下面那样，返回给前台一些数值使用
//        if($num > 0){
//           $data = "0";
//        }else{
//            $data = "1";
//        }

//        $this->ajaxReturn($data);//ajax 返回值，其实没这个必要，不过这里是想学习一下thinkphp的ajax 返回罢了
    }

    //保存更改
    public function saveChange()
    {
        $count = I('count');
        $info = I('info');
        $id = I('billid');

        $m = M('Billinfo');
        $where['id'] = $id;
        $data['count'] = $count;
        $data['info'] = $info;

        $num = $m->where($where)->save($data);
        if ($num) {
            $this->success("更新成功！", U("Index/index"));
        } else {
            $this->error("更新失败");
        }

    }

}
