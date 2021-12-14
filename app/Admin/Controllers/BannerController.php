<?php

namespace App\Admin\Controllers;

use App\Models\Banner;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class BannerController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Banner(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('image')->image();
            $grid->column('url');
            $grid->column('type');

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');

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
        return Show::make($id, new Banner(), function (Show $show) {
            $show->field('id');
            $show->field('image')->image();
            $show->field('url')->link();
            $show->field('type')->using(['1' => '首页']);
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Banner(), function (Form $form) {
            $form->display('id');
            $form->image('image')->required()->autoUpload()->removable(false)->uniqueName();
            $form->text('url');
            $form->select('type')->options([1 => '首页'])->default(1);

            Controller::saveFile($form,'image');

            Controller::takeOutFooter($form);
        });
    }
}
