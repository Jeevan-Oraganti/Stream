<?php
$tabs = [
    ['title' => 'Electronics', 'slug' => 'electronics', 'id' => 1, 'content' => null],
    ['title' => 'Clothing', 'slug' => 'clothing', 'id' => 2, 'content' => null],
    ['title' => 'Books', 'slug' => 'books', 'id' => 3, 'content' => null],
    ['title' => 'Movies', 'slug' => 'movies', 'id' => 4, 'content' => null],
    ['title' => 'Travel', 'slug' => 'travel', 'id' => 5, 'content' => null],
    ['title' => 'Food', 'slug' => 'food', 'id' => 6, 'content' => null],
    ['title' => 'Fitness', 'slug' => 'fitness', 'id' => 7, 'content' => null],
    ['title' => 'Gaming', 'slug' => 'gaming', 'id' => 8, 'content' => null],
    ['title' => 'Grocery', 'slug' => 'grocery', 'id' => 9, 'content' => null],
    ['title' => 'Other', 'slug' => 'other', 'id' => 10, 'content' => null],
];
?>

<section>
    <div class="p-8">
        <div class="border border-gray-200 p-6 mb-12 rounded-xl">
            <h1 class="text-3xl font-semibold mb-8">Explore Categories</h1>
            <tabs :tabs='@json($tabs)' />
        </div>
    </div>
</section>