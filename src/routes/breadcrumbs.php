<?php

Breadcrumbs::for('home', function ($trail) {
    $trail->push(__('Home'), route('home'));
});

Breadcrumbs::for('product-importer.index', function ($trail) {
    $trail->parent('home');
    $trail->push(__('Product import'), route('product-importer.index'));
});

Breadcrumbs::for('product-importer.history', function ($trail) {
    $trail->parent('product-importer.index');
    $trail->push(__('History of imports'), route('product-importer.history'));
});
