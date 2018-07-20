<!DOCTYPE html>
<html>
<head>
    <title>Laravel Category Treeview Example</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link href="{{ asset('vendor/categoryTreeview/css/treeview.css') }}" rel="stylesheet" type="text/css"/>
</head>
<body>
<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">Manage Category TreeView</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <h3>Category List</h3>
                    <ul id="tree1">
                        @foreach($categories as $category)
                            <li>
                                {{ $category->name }}

                                @if(count($category->children))

                                    <span class="branch caret"></span>

                                    @include('categories.manageChild',['childs' => $category->children])
        @else
            <a href="http://localhost/enatural/public/pricelists" class="btn btn-warning">Cancelar</a>

                                @endif

                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-md-6">
                    <h3>Add New Category</h3>

                    {!! Form::open(['route'=>'add.category']) !!}

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif

                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        {!! Form::label('Name:') !!}
                        {!! Form::text('name', old('name'), ['class'=>'form-control', 'placeholder'=>'Enter Name']) !!}

                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    </div>
                    <div class="form-group {{ $errors->has('parent_id') ? 'has-error' : '' }}">

                        {!! Form::label('Category:') !!}

                        {!! Form::select('parent_id',$allCategories, old('parent_id'), ['class'=>'form-control', 'placeholder'=>'Select Category']) !!}

                        <span class="text-danger">{{ $errors->first('parent_id') }}</span>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success" disabled="disabled">Add New</button>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('vendor/categoryTreeview/js/treeview.js') }}" type="text/javascript"></script>
</body>
</html>