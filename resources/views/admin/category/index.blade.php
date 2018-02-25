@extends('layouts.admin')
@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="{{url('admin/info')}}">首页</a> &raquo;<a href="{{url('admin/category')}}">分类列表</a>
        @if(count($nav) !=0)
        @foreach($nav as $key =>$item)
        @if($key == count($nav)-1)
        &raquo;{{$item->name}}
        @else
        &raquo;<a href="{{url('admin/category/'.$item->id)}}">{{$item->name}}</a>
        @endif
        @endforeach
        @endif
    </div>

    <div class="result_wrap">
        <!--快捷导航 开始-->
        <div class="result_content">
            <div class="short_wrap">
                <a onclick="createCategory('add')"><i class="fa fa-plus"></i>新增分类</a>
            </div>
        </div>
        <!--快捷导航 结束-->
    </div>

    <div class="result_wrap">
        <div class="result_content">
            <table class="list_tab">
                <tr>
                    <th class="tc" width="5%">排序</th>
                    <th class="tc" width="5%">ID</th>
                    <th>分类名称</th>
                    <th>标题</th>
                    <th>关键字</th>
                    <th>查看次数</th>
                    <th>更新时间</th>
                    <th>操作</th>
                </tr>
                @foreach($data as $v)
                    <tr>
                        <td class="tc">
                            <input type="text" onchange="changeOrder({{$v->id}})" value="{{$v->order}}">
                        </td>
                        <td class="tc">{{$v->id}}</td>
                        <td>
                            @if($v->child)
                                <a href="{{url('admin/category/'.$v->id)}}">{{$v->name}}</a>
                            @else
                                {{$v->name}}
                            @endif
                        </td>
                        <td>{{$v->title}}</td>
                        <td>{{$v->keywords}}</td>
                        <td>{{$v->view}}</td>
                        <td>{{$v->created_at}}</td>
                        <td>
                            <a data-id="{{$v->id}}" onclick="createCategory('edit')">修改</a>
                            <a onclick="deleteCate({{$v->id}})">删除</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>


    <div class="modal fade" id="add" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">添加分类</h4>
                </div>
                <div class="modal-body form-horizontal">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">父级分类</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="pid" id="allcat">
                                <option value="0">顶级分类</option>
                            </select>
                        </div>

                    </div>
                    <div class="form-group" id="name">
                        <label class="col-sm-2 control-label">分类名称</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" class="form-control" placeholder="分类名称">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">分类描述</label>
                        <div class="col-sm-9">
                            <input type="text" name="title" class="form-control" placeholder="分类描述">

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">关键词</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="keywords" placeholder="关键词"></textarea></div>

                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">分类简介</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="description" placeholder="分类简介"></textarea></div>

                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">排序</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" name="order" value="0" placeholder="排序">
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" onclick="submit()" class="btn btn-primary">保存</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function changeOrder(id) {
            $.get('/admin/cate/changeOrder', {
                _token: '{{csrf_token()}}',
                cate_id: id,
                order: $(event.target).val()
            }, function (res) {
                layer.alert(res.msg, {icon: res.status == 0 ? 6 : 5}, function (index) {
                    window.location.reload();
                    layer.close(index);
                });
            })
        }

        function deleteCate(id) {
            layer.confirm('将删除当前分类下所有分类且无法恢复?', {icon: 3, title: '提示'}, function () {
                $.post('/admin/cate/deleteCategory', {_token: '{{csrf_token()}}', id: id}, function (res) {
                    layer.alert(res.msg, {icon: res.status == 0 ? 6 : 5}, function (index) {
                        layer.close(index);
                        window.location.reload();
                    });
                })
            });

        }

        function createCategory(action) {
            if (action != 'add') {
                $('#add .modal-title').text('修改分类');
                $.get('/admin/category/'+$(event.target).attr('data-id')+'/edit',function (res) {
                    $("input[name='name']").val(res.name);
                    $("input[name='order']").val(res.order);
                    $("textarea[name='description']").val(res.description);
                    $("textarea[name='keywords']").val(res.keywords);
                    $("input[name='title']").val(res.title);
                    var tree = Atree()
                    $.get('/admin/category/create', function (res1) {
                        var html = "<option value='0'>顶级分类</option>";
                        if (res1.data) {
                            tree(res1.data).forEach(function (t) {
                                html += "<option value=" + t.id + ">" + t.name + "</option>"
                            })
                        }
                        $('#allcat').html(html)
                        $("select[name='pid']").val(res.pid)
                    })
                })
            }else {
                var tree = Atree()
                $.get('/admin/category/create', function (res) {
                    var html = "<option value='0'>顶级分类</option>";
                    if (res.data) {
                        tree(res.data).forEach(function (t) {
                            html += "<option value=" + t.id + ">" + t.name + "</option>"
                        })
                    }
                    $('#allcat').html(html)
                })
            }
            $('#add').modal('toggle');
        }

        function submit() {
            if (!$("input[name='name']").val().trim()) {
                var html = "<div class='alert alert-danger alert-dismissable' style='margin-bottom:10px;padding-top:0;padding-bottom: 0'><button type='button' class='close' data-dismiss='alert'aria-hidden='true'>&times; </button>名称不能为空</div>";
                $(".modal-body").prepend(html);
                $("#name").addClass('has-error');
                return false
            }

            if($('#add .modal-title').text()!='修改分类'){
                $.post('/admin/category', {
                        _token: '{{csrf_token()}}',
                        pid: $("select[name='pid']").val(),
                        name: $("input[name='name']").val(),
                        order: $("input[name='order']").val(),
                        description: $("textarea[name='description']").val(),
                        keywords: $("textarea[name='keywords']").val(),
                        title: $("input[name='title']").val()
                    },
                    function (res) {
                        layer.alert(res.msg, {icon: res.status == 0 ? 6 : 5}, function (index) {
                            layer.close(index);
                            if (res.status == '1') {
                                return false
                            }
                            $('#add').modal('toggle');
                            window.location.reload();
                        });
                    })
            }else {

            }

        }

        function Atree() {
            var trees = [];
            var formattree = function (data) {
                data.forEach(function (t) {
                    trees.push(t)
                    if (t.child) {
                        formattree(t.child)
                    }
                })
                return trees
            };
            return formattree
        }

        $('#add').on('hidden.bs.modal', function (e) {
            $(".alert").remove();
            $("#name").removeClass('has-error');
        })

    </script>
@endsection