<?php

return [
    [
        'key'   => 'finance',
        'name'  => 'Finance',
        'route' => 'admin.cost_management.report.index',
        'sort'  => 6,
        'icon'  => 'icon-sales',
    ], [
        'key'   => 'finance.report',
        'name'  => 'P&L Dashboard',
        'route' => 'admin.cost_management.report.index',
        'sort'  => 1,
        'icon'  => 'icon-graph',
    ], [
        'key'   => 'finance.product-costs',
        'name'  => 'Product Costs',
        'route' => 'admin.cost_management.products.index',
        'sort'  => 2,
        'icon'  => 'icon-sort',
    ], [
        'key'   => 'finance.expenses',
        'name'  => 'General Expenses',
        'route' => 'admin.cost_management.expenses.index',
        'sort'  => 3,
        'icon'  => 'icon-invoice',
    ], [
        'key'   => 'finance.shareholders',
        'name'  => 'Shareholders',
        'route' => 'admin.cost_management.shareholders.index',
        'sort'  => 4,
        'icon'  => 'icon-peoples',
    ], [
        'key'   => 'finance.distributions',
        'name'  => 'Profit Distributions',
        'route' => 'admin.cost_management.distributions.index',
        'sort'  => 5,
        'icon'  => 'icon-money',
    ],
];
