<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Hash;

class UserController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new User(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('account');
            $grid->column('phone');
            $grid->column('email');
            $grid->column('avatar')->image('',80,80);
            $grid->column('last_login_at');
            $grid->column('last_login_ip');
            $grid->column('register_ip');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
                $filter->like('name', '昵称');
                $filter->like('phone', '手机号');
                $filter->like('email', '邮箱');
                $filter->like('last_login_ip', '最后一次登陆ip');
                $filter->like('register_ip', '注册ip');
                $filter->between('last_login_at','最后一次登录时间')->date();
                $filter->between('created_at','注册时间')->date();
            });
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new User(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('account');
            $show->field('phone');
            $show->field('email');
            $show->field('email_verified_at');
            $show->field('avatar')->image();
            $show->field('introduction');
            $show->field('last_login_at');
            $show->field('last_login_ip');
            $show->field('register_ip');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new User(), function (Form $form) {
            $form->display('id');
            $form->text('name')->required();
            $form->text('account')->rules(function (Form $form) {
                // 如果不是编辑状态，则添加字段唯一验证
                if (!empty($form->email) && !$id = $form->model()->id) {
                    return 'unique:users,account';
                }
            });
            $form->text('phone')->required()->rules(function (Form $form) {
                // 如果不是编辑状态，则添加字段唯一验证
                if (!$id = $form->model()->id) {
                    return 'unique:users,phone';
                }
            });
            $form->text('email')->rules(function (Form $form) {
                // 如果不是编辑状态，则添加字段唯一验证
                if (!empty($form->email) && !$id = $form->model()->id) {
                    return 'unique:users,email';
                }
            });
            $form->datetime('email_verified_at');
            $form->password('password');
            $form->image('avatar')->autoUpload()->removable(false)->uniqueName();
            $form->text('introduction');
            $form->display('last_login_at');
            $form->display('last_login_ip');
            $form->display('register_ip');

            $form->display('created_at');
            $form->display('updated_at');

            $form->saving(function (Form $form){
                if ($form->password) {
                    $form->input('password', Hash::make($form->password));
                }else{
                    $form->deleteInput('password');
                }
            });

            Controller::saveFile($form,'avatar');

            Controller::takeOutFooter($form);
        });
    }
}
