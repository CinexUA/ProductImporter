@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('product-importer.index') }}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Product import') }}</div>

                <div class="card-body">

                    {!! Form::open(['route' => ['product-importer.store'], 'files' => true]) !!}

                    <div class="form-group">
                        {!! Form::label('products-input',"Excel with products. Format: <strong>.xls,.xlsx</strong>",[],false)!!}
                        {{Form::file('products', [
                            'id' => 'products-input',
                            'class' => "form-control". ($errors->has('products') ? ' is-invalid' : ''),
                            'accept' => "application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                            ]
                        )}}

                        @error("products")
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        {{Form::submit('Submit', ['onclick' => 'showLoader()'])}}
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
