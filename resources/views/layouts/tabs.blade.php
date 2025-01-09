<?php
$tabs = [
    ['title' => 'Electronics', 'slug' => 'electronics', 'id' => 1],
    ['title' => 'Clothing', 'slug' => 'clothing', 'id' => 2],
    ['title' => 'Books', 'slug' => 'books', 'id' => 3],
    ['title' => 'Movies', 'slug' => 'movies', 'id' => 4],
    ['title' => 'Travel', 'slug' => 'travel', 'id' => 5],
    ['title' => 'Food', 'slug' => 'food', 'id' => 6],
    ['title' => 'Fitness', 'slug' => 'fitness', 'id' => 7],
    ['title' => 'Gaming', 'slug' => 'gaming', 'id' => 8],
    ['title' => 'Grocery', 'slug' => 'grocery', 'id' => 9],
    ['title' => 'Other', 'slug' => 'other', 'id' => 10],
];
?>


<section>
    <div class="p-8">

        <div class="border border-gray-200 p-6 mb-12 rounded-xl">
            <h1 class="text-3xl font-semibold mb-8">Explore Categories</h1>
            <tabs :tabs='@json($tabs)'/>
        </div>
    </div>
</section>
