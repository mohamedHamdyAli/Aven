@extends('admin::layouts.master')

@section('page_title')
    {{ trans('abandoned-cart::app.admin.title') }}
@stop

@section('content-wrapper')
    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ trans('abandoned-cart::app.admin.title') }}</h1>
            </div>
        </div>

        <div class="page-content">
            <x-admin::datagrid :src="route('admin.sales.abandoned-carts.index')" />
        </div>
    </div>
@stop
