<?php

namespace App\Admin\Controllers;

use Dcat\Admin\Form;

//存放静态方法
class Controller
{
    /**
     * 去掉查看,继续编辑,继续创建
     * @param $form
     * @return mixed
     */
    public static function takeOutFooter($form)
    {
        return $form->footer(function ($footer) {
            // 去掉`查看`checkbox
            $footer->disableViewCheck();

            // 去掉`继续编辑`checkbox
            $footer->disableEditingCheck();

            // 去掉`继续创建`checkbox
            $footer->disableCreatingCheck();
        });
    }

    /**
     * 保存单文件到数据库
     * @param $form
     * @param $field
     */
    public static function saveFile($form, $field)
    {
        $form->saving(function (Form $form) use ($field) {
            if ($form->model()->$field != $form->$field) {
                $form->$field = '/files/' . $form->$field;
            }
        });
    }
}
