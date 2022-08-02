<?php

namespace App\Admin\Controllers;

use App\Models\Fragment;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class FragmentController extends AdminController
{
    protected $options = ['rich_text' => '富文本', 'text' => '文本', 'json' => '数组字符串', 'image' => '图片', 'file' => '文件', 'map' => '地图'];
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Fragment(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('title');
            $options = $this->options;
            $grid->column('type')->display(function () use ($options) {
                return $options[$this->type];
            });

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
                $filter->like('title');
                $filter->equal('type')->select($this->options);
            });

            Controller::forbidAddOrDelete($grid,'grid');
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
        return Show::make($id, new Fragment(), function (Show $show) {
            $show->field('id');
            $show->field('title');
            $options = $this->options;
            $show->field('type')->unescape()->as(function () use ($options) {
                return $options[$this->type];
            });
            switch ($show->model()->type) {
                case 'rich_text':
                    $show->field('content')->unescape()->as(function ($content) {
                        return $content;
                    });
                    break;
                case 'text':
                    $show->field('content');
                    break;
                case 'json':
                    $data = json_decode($show->model()->content, true);
                    $main = '';
                    foreach ($data as $index => $item) {
                        $main .= '<tr>
                                  <td>' . $item['key'] . '</td>
                                  <td>' . $item['value'] . '</td>
                                  <td>' . $item['desc'] . '</td>
                                </tr>';
                    }
                    $html = '<table class="table">
                              <tbody>
                                <thead>
                                    <tr>
                                      <th scope="col">键</th>
                                      <th scope="col">值</th>
                                      <th scope="col">备注</th>
                                    </tr>
                                  </thead>
                              ' . $main . '
                              </tbody>
                            </table>';

                    $show->field('content')->unescape()->as(function ($content) use ($html) {
                        return $html;
                    });
                    break;
                case 'image':
                    $show->field('content')->image();
                    break;
                case 'file':
                    $show->field('content')->file(config('app.url'));
                    break;
                case 'map':
                    $coordinate = json_decode($show->model()->content, true);
                    $show->html(view('admin/map',['coordinate'=>$coordinate]));
                    break;
            }

            Controller::forbidAddOrDelete($show,'show');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Fragment(), function (Form $form) {
            $form->display('id');
            $form->text('title')->required()->disable(!config('app.debug'));

            $type = $form->input('type');
            $modelType = $form->model()->type;
            $form->model()->$modelType = $form->model()->content;

            if ($type == 'map') {
                $coordinate = json_decode($form->model()->content, true);
                $form->model()->lat = $coordinate['lat'];
                $form->model()->lng = $coordinate['lng'];
            }

            $form->select('type')
                ->when('rich_text', function (Form $form) {
                    $form->editor('rich_text', '内容');
                })
                ->when('text', function (Form $form) {
                    $form->text('text', '内容');
                })
                ->when('json', function (Form $form) {
                    $form->table('json', function (Form\NestedForm $table) {
                        $table->text('key', '键');
                        $table->text('value', '值');
                        $table->text('desc', '备注');
                    })->label('内容');
                })
                ->when('image', function (Form $form) {
                    $form->image('image', '内容')->autoUpload()->removable(false)->uniqueName();
                })
                ->when('file', function (Form $form) {
                    $form->file('file', '内容')->autoUpload()->removable(false)->uniqueName();
                })
                ->when('map', function (Form $form) {
                    $form->map('lat','lng', '内容');
                })
                ->options($this->options)
                ->default('rich_text')->disable(!config('app.debug'));
            $form->hidden('content');

            $form->saving(function (Form $form) use ($type) {
                $form->content = $form->$type;
                $form->type = $type;

                if ($type == 'image' && $form->model()->content != $form->image) {
                    $form->content = '/files/' . $form->content;
                }

                if ($type == 'file' && $form->model()->content != $form->file) {
                    $form->content = '/files/' . $form->content;
                }

                if ($type == 'map') {
                    $form->content = [
                        'lat' => $form->lat,
                        'lng' => $form->lng,
                    ];
                }

                if (!$form->content) {
                    return $form->response()->error('内容不能为空');
                }

                unset($form->model()->rich_text);
                unset($form->model()->text);
                unset($form->model()->json);
                unset($form->model()->image);
                unset($form->model()->file);
                unset($form->model()->map);
                unset($form->model()->lat);
                unset($form->model()->lng);

                $form->deleteInput(['rich_text', 'text', 'json', 'image', 'file', 'map', 'lat', 'lng']);
            });

            Controller::takeOutFooter($form);
            Controller::forbidAddOrDelete($form,'form');
        });
    }
}
