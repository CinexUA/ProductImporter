@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('product-importer.history.log', $historyLog) }}
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
                                        <th>{{__('Row')}}</th>
                                        <th>{{__('Description')}}</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($historyLog as $item)
                                        <tr>
                                            <td>{{$item->row}}</td>
                                            <td>{{$item->description}}</td>
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
                            {{ $historyLog->appends(request()->except('page'))->render() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
