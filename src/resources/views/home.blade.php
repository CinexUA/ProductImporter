@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('home') }}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p>
                        <a href="{{route('product-importer.index')}}">{{__('Product import')}}</a> |
                        <a href="{{route('product-importer.history')}}">{{__('History of imports')}}</a>
                    </p>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <a href="{{route('reset')}}" class="btn btn-warning" onclick="showLoader()">
                                {{__('Reset Products, Categories, Manufactures, Import history')}}
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th style="width: 55px">{{__('Id')}}</th>
                                        <th>{{__('Name')}}</th>
                                        <th>{{__('Category')}}</th>
                                        <th>{{__('Manufacturer')}}</th>
                                        <th>{{__('Vendor code')}}</th>
                                        <th>{{__('Description')}}</th>
                                        <th>{{__('Price')}}</th>
                                        <th>{{__('Guarantee')}}</th>
                                        <th>{{__('Availability')}}</th>
                                        <th>{{__('Created At')}}</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($products as $product)
                                        <tr>
                                            <td>{{$product->getKey()}}</td>
                                            <td>{{$product->name}}</td>
                                            <td>{{$product->category->name}}</td>
                                            <td>{{$product->manufacturer->name}}</td>
                                            <td>{{$product->vendor_code}}</td>
                                            <td>{{Str::limit($product->description, 20)}}</td>
                                            <td>{{$product->getPrice()}}</td>
                                            <td>{{$product->guarantee}}</td>
                                            <td>{{$product->getHumanAvailability()}}</td>
                                            <td>{{$product->created_at->diffForHumans()}}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%">
                                                {{__('No entries')}}
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <div class="col-12">
                            {{ $products->appends(request()->except('page'))->render() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
