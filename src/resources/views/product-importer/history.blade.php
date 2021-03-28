@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('product-importer.history') }}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('History of imports') }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th style="width: 55px">{{__('Id')}}</th>
                                        <th>{{__('Name')}}</th>
                                        <th>{{__('Fail count')}}</th>
                                        <th>{{__('Processed')}}</th>
                                        <th>{{__('Total')}}</th>
                                        <th>{{__('Status')}}</th>
                                        <th>{{__('Uploaded')}}</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($imports as $import)
                                        <tr>
                                            <td>{{$import->getKey()}}</td>
                                            <td>{{$import->name}}</td>
                                            <td>
                                                <a href="{{route('product-importer.history.log', $import)}}">
                                                    {{$import->fail_count}}
                                                </a>
                                            </td>
                                            <td>{{$import->processed}}</td>
                                            <td>{{$import->total}}</td>
                                            <td>{{$import->getHumanStatus()}}</td>
                                            <td>{{$import->created_at->diffForHumans()}}</td>
                                            <td>
                                                <a
                                                    class="btn btn-danger btn-sm delete"
                                                    href="#"
                                                    role="button"
                                                    data-id="{{$import->getKey()}}"
                                                    data-name="{{$import->name}}"
                                                    data-url="{{route('product-importer.history.remove', $import->getKey())}}"
                                                >
                                                    X
                                                </a>
                                            </td>
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
                            {{ $imports->appends(request()->except('page'))->render() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
