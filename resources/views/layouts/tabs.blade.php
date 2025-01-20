<?php
$tabs = [
    ['title' => 'Electronics', 'slug' => 'electronics', 'content' => null],
    ['title' => 'Clothing', 'slug' => 'clothing', 'content' => null],
    ['title' => 'Books', 'slug' => 'books', 'content' => null],
    ['title' => 'Movies', 'slug' => 'movies', 'content' => null],
    ['title' => 'Travel', 'slug' => 'travel', 'content' => null],
    ['title' => 'Food', 'slug' => 'food', 'content' => null],
    ['title' => 'Fitness', 'slug' => 'fitness', 'content' => null],
    ['title' => 'Gaming', 'slug' => 'gaming', 'content' => null],
    //  ['title' => 'Grocery', 'slug' => 'grocery', 'content' => null],
    //  ['title' => 'Other', 'slug' => 'other', 'content' => null],
];
?>

<section>
    <div class="p-8">
        <div class="border border-gray-200 p-5 mb-10 rounded-xl">
            <h1 class="text-3xl font-semibold mb-8">Explore Categories</h1>
            <tabs :tabs='@json($tabs)' />
        </div>
    </div>
</section>
