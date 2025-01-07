<?php
$tabs = [
    ['title' => 'Electronics', 'id' => 1],
    ['title' => 'Clothing', 'id' => 2],
    ['title' => 'Books', 'id' => 3],
    ['title' => 'Movies', 'id' => 4],
    ['title' => 'Travel', 'id' => 5],
    ['title' => 'Food', 'id' => 6],
    ['title' => 'Fitness', 'id' => 7],
    ['title' => 'Gaming', 'id' => 8],
    ['title' => 'Grocery', 'id' => 9],
    ['title' => 'Other', 'id' => 10],
];
?>


<section>
    <div class="p-8">

        <div class="border border-gray-200 p-6 mb-12 rounded-xl">
            <h1 class="text-3xl font-semibold mb-8">Explore Categories</h1>
            <tabs :tabs='@json($tabs)' @tab-selected="handleTabSelected" />
        </div>
    </div>
</section>