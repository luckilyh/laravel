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
        if (!is_array($field)) {
            $field = [$field];
        }

        foreach ($field as $index => $item) {
            preg_match('/^images.*/', $form->$item, $match);
            if ($match) {
                $form->$item = '/files/' . $form->$item;
            }
        }
    }

    /**
     * 在正式环境下禁止用户删除/添加
     * @param $module
     * @param $type
     */
    public static function forbidAddOrDelete($module,$type)
    {
        if (!config('app.debug')) {
            switch ($type) {
                case 'grid':
                    // 禁用删除按钮
                    $module->disableDeleteButton();
                    // 禁用批量删除按钮
                    $module->disableBatchDelete();
                    // 禁用行选择器
                    $module->disableRowSelector();
                    // 禁用创建按钮
                    $module->disableCreateButton();
                    break;
                case 'show':
                    $module->panel()
                        ->tools(function ($tools) {
                            $tools->disableDelete();
                        });
                    break;
                case 'form':
                    $module->disableDeleteButton();
                    break;
            }
        }
    }
}
