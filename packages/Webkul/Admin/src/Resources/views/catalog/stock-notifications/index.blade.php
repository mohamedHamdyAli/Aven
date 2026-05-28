@extends('admin::layouts.master')

@section('title')
    @lang('admin::app.catalog.stock-notifications.title')
@endsection

@section('content')
    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.catalog.stock-notifications.title')
        </p>
    </div>

    <x-admin::datagrid :src="route('admin.catalog.stock_notifications.index')" />
@endsection
