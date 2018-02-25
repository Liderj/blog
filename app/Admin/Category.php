<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  protected $table = 'category';
  protected $guarded = [];
  protected $arr = [];

  public function getDateTree($pid)
  {
    $category = $this->where('pid', $pid)->orderBy('order')->get();
    foreach ($category as $key => $item) {
      if (empty($this->where('pid', $category[$key]['id'])->first())) {
        $category[$key]['child'] = false;
      } else {
        $category[$key]['child'] = true;
      }
    }
    return $category;
  }

  public function getCategoryNav($id)
  {
    $self = $this->where('id', $id)->first();
    if ($self) {
      array_unshift($this->arr, $self);
      $this->getCategoryNav($self->pid);
    }
    return $this->arr;
  }


  public function getAll($pid=0,$lv=0)
  {
    $data= $this->where('pid', $pid)->get();
    $lv++;
    if(!empty($data)){
      $tree = array();
      foreach ($data as $val) {
        $val['name'] = str_repeat('|— ', $lv-1).$val['name'];
        $child = $this ->getAll($val['id'],$lv);
        if(empty($child)){
          $child = null;
        }
        $val['child'] = $child;
        $tree[] = array('name'=>$val->name,'id'=>$val->id,'child'=>$val->child);
      }
    }
    return $tree;
  }

}
