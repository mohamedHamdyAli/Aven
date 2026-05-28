<?php

use Webkul\CostManagement\Models\GeneralExpense;

it('allows mass assignment of fillable fields', function () {
    $expense = new GeneralExpense([
        'title'        => 'Monthly Rent',
        'category'     => 'rent',
        'amount'       => 5000.00,
        'expense_date' => '2024-05-01',
        'is_recurring' => true,
        'frequency'    => 'monthly',
        'notes'        => 'Office rent',
    ]);

    expect($expense->title)->toBe('Monthly Rent')
        ->and($expense->category)->toBe('rent')
        ->and($expense->amount)->toBe(5000.00)
        ->and($expense->is_recurring)->toBeTrue();
});

it('casts expense_date to date', function () {
    $expense = new GeneralExpense(['expense_date' => '2024-05-01']);

    expect($expense->expense_date)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('casts is_recurring to boolean', function () {
    $expense = new GeneralExpense(['is_recurring' => 1]);

    expect($expense->is_recurring)->toBeTrue();
});

it('casts amount to float', function () {
    $expense = new GeneralExpense(['amount' => '3500']);

    expect($expense->amount)->toBeFloat();
});

it('categories static method returns array with expected keys', function () {
    $categories = GeneralExpense::categories();

    expect($categories)->toBeArray()
        ->and($categories)->toHaveKey('rent')
        ->and($categories)->toHaveKey('salaries')
        ->and($categories)->toHaveKey('marketing');
});

it('creates a record via factory', function () {
    $expense = GeneralExpense::factory()->create();

    expect($expense->exists)->toBeTrue()
        ->and($expense->amount)->toBeFloat();
});

it('recurring state sets is_recurring and frequency', function () {
    $expense = GeneralExpense::factory()->recurring()->create();

    expect($expense->is_recurring)->toBeTrue()
        ->and($expense->frequency)->toBe('monthly');
});
