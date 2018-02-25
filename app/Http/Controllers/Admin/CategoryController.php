<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;

class CategoryController extends CommonController
{
  /**
   * 分类列表.
   */
  public function index(Request $request)
  {
    return $this->show(0);
  }
//  ajax返回分类树
  public function create()
  {
    $categorys=(new Category)->getAll();
    return ['status'=>0,'data'=>$categorys];
  }
//  新建分类
  public function store(Request $request)
  {
    $name = Category::where('name',$request->name)->first();
    if($name){
      return ['status' => 1, 'msg' => '分类已经存在，请重新命名'];
    }
    $messages = ['name.required' => '名称不能为空'];
    $validator = Validator::make($request->except(['_token']), ['name' => 'required'],$messages);
    if($validator->passes()){
      Category::create($request->except(['_token']));
      return ['status' => 0, 'msg' => '分类创建成功'];
    }
    return ['status' => 1, 'msg' => $validator->errors()->first()];
  }

//页面模板
  public function show($id)
  {
    $categorys = (new Category)->getDateTree($id);
    $nav = (new Category)->getCategoryNav($id);
    return view('admin.category.index')->with(['data' => $categorys, 'nav' => $nav]);
  }
//排序更改
  public function changeOrder()
  {
    $req = Input::all();
    $cate = Category::find($req['cate_id']);
    $cate->order = $req['order'];
    $result = $cate->update();
    if ($result) {
      return ['status' => 0,
        'msg' => '更新成功'];
    }
    return ['status' => 1,
      'msg' => '更新失败'];
  }
//修改分类
  public function editOne()
  {

  }
//修改分类
  public function edit($id)
  {
    $category = Category::where('id',$id)->first();
    return $category;
  }

//删除分类
  public function deleteCategory()
  {
    $id = Input::all()['id'];
    $deleteRows = Category::where('id', $id)->orWhere('pid', $id)->delete();
    if ($deleteRows) {
      return ['status' => 0, 'msg' => '删除成功'];
    }
    return ['status' => 1, 'msg' => '删除失败'];
  }
}
