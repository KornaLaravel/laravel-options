<?php

use Spatie\LaravelOptions\Options;
use Spatie\LaravelOptions\Providers\MyClabsEnumProvider;
use Spatie\LaravelOptions\Tests\Fakes\MyclabsEnum\MyClabsEnum;
use Spatie\LaravelOptions\Tests\Fakes\MyclabsEnum\MyClabsEnumHumanLabels;

it('can create options from a myclabs enum', function () {
    $options = Options::forProvider(new MyClabsEnumProvider(MyClabsEnum::class))->toArray();

    expect($options)->toBeArray()->toBe([
        ['label' => 'Frodo', 'value' => 'frodo'],
        ['label' => 'Sam', 'value' => 'sam'],
        ['label' => 'Merry', 'value' => 'merry'],
        ['label' => 'Pippin', 'value' => 'pippin'],
    ]);
});

it('will humanize a myclabs enum label by default', function () {
    $options = Options::forProvider(new MyClabsEnumProvider(MyClabsEnumHumanLabels::class))->toArray();

    expect($options)->toBeArray()->toBe([
        ['label' => 'Frodo', 'value' => 'frodo'],
        ['label' => 'Sam gamgee', 'value' => 'sam gamgee'],
        ['label' => 'Merry', 'value' => 'merry'],
        ['label' => 'Pippin', 'value' => 'pippin'],
    ]);
});

it('can use only and except on a myclabs enum options', function () {
    $options = Options::forProvider(new MyClabsEnumProvider(MyClabsEnum::class))
        ->only(MyClabsEnum::Frodo, MyClabsEnum::Sam)
        ->toArray();

    expect($options)->toBeArray()->toBe([
        ['label' => 'Frodo', 'value' => 'frodo'],
        ['label' => 'Sam', 'value' => 'sam'],
    ]);

    $options = Options::forProvider(new MyClabsEnumProvider(MyClabsEnum::class))
        ->except(MyClabsEnum::Frodo, MyClabsEnum::Sam)
        ->toArray();

    expect($options)->toBeArray()->toBe([
        ['label' => 'Merry', 'value' => 'merry'],
        ['label' => 'Pippin', 'value' => 'pippin'],
    ]);
});
