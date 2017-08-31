
@extends('layouts.backend')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading">
      <div class="row">
        <div class="col-sm-8">
            {name}
        </div>
        <div class="col-sm-4 text-center">
            <a href="#" class="btn btn-success btn-add pull-right" data-toggle="modal" data-target="#formModal">Добавить</a>
        </div>
      </div>
    </div>
   
    <div class="panel-body">
        <div class="table-responsive">
            <table id="datatable" class="data-table table table-hover table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Заголовок</th>
                        <th>Текст</th>
                        <th width="100"></th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Добавить</h4>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" role="tablist">
                    @foreach(Config::get('app.supported_locales') as $i=>$v)
                        <li role="presentation" @if($i==0)class="active" @endif><a href="#{{$v}}" aria-controls="{{$v}}"
                        role="tab" data-toggle="tab"
                        style="text-transform: uppercase">{{$v}}</a>
                        </li>
                    @endforeach    
                </ul>
                <form action="" id="form">
                    {!! Form::hidden('id', null) !!}
                    <div class="tab-content">
                        @foreach(Config::get('app.supported_locales') as $i=>$v)
                        <div role="tabpanel" class="tab-pane fade in @if($i==0) active @endif" id="{{$v}}">
                            <div class="form-group">
                                {!! Form::label('title', 'Заголовок') !!}
                                {!! Form::text('title_'.$v, null, ["class" => "form-control"]) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('description', 'Описание') !!}
                                {!! Form::text('description_'.$v, null, ['class' => 'form-control']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('url', 'Cсылка') !!}
                                {!! Form::text('url_'.$v, null, ['class' => 'form-control']) !!}
                            </div>

                        </div>  
                        @endforeach  
                    </div>
                    
                    <div class="form-group">
                        {!! Form::label('image', 'Изображение') !!}
                        {!! Form::file('image',null, ['class' => 'form-control']) !!}
                    </div>    
                </form>
            </div>
            <div class="modal-footer">
                <div class="progress js-progress-bar hide">
                    <div class="progress-bar"></div>
                </div>
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                <button type="submit" class="btn btn-primary ajax-form">Сохранить</button>
            </div>
        </div>
    </div>
</div>
  
@endsection

@section('scripts')

@include('partials.ajax-delete')
<script src="{{ asset('backend/js/datatables.min.js') }}"></script>
<script src="{{ asset('backend/js/crud.js') }}"></script>
<script>

    var crud = new Crud({
        form: {
            url: '{{ action("Backend\{Controller}@postForm") }}',
        },

        list: {
            url: '{{ action("Backend\{Controller}@getData") }}',
            datatable: {
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'title_ru', name: 'title_ru'},
                    {data: 'description_ru', name: 'description_ru'},
                ],
                columnDefs: [
                    {
                        targets: 3,
                        data: null,
                        searchable:false, 
                        render: function (row, type, val, meta) {
                            return crud.makeButton(val, 'btn-primary btn-edit', '<i class="fa fa-pencil"></i>', [
                                ['toggle', 'modal'],
                                ['target', '#formModal']
                            ])
                            + crud.makeButton(val, 'btn-danger', '<i class="fa fa-trash"></i>', [
                                ['toggle', 'modal'],
                                ['target', '#removeModal']
                            ]);
                        }
                    }
                ]
            }
        },

        remove: {
            url: '{{ action("Backend\{Controller}@postDelete") }}',
        }
    });

</script>

@stop