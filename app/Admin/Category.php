<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  protected $table = 'category';

  public function getDateTree($pid)
  {
    $category = $this->where('pid', $pid)->get();
    foreach ($category as $key => $item) {
      if (empty($this->where('pid', $category[$key]['id'])->first())) {
        $category[$key]['child'] = false;
      } else {
        $category[$key]['child'] = true;
      }
    }
    return $category;
  }

  public function getPid($id)
  {
    $arr = array();
//    dd($this->where('id', $id)->first()->pid);
    $self = $this->where('id', $this->where('id', $id)->first()->pid)->first();
    array_push($arr,$self);
    if ($self->pid != 0) {
      $this->getPid($self->pid);
    }

    return $arr;
  }

}
