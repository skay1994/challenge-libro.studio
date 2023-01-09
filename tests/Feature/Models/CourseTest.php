<?php

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('Course list all', function () {
    $courses = Course::factory(10)->create();
    $course = $courses->first();
    $response = $this->getJson(route('courses.index'));

    $response->assertOk();
    $response->assertJson([
        'data' => [
            [
                'id' => $course->getKey(),
                'title' => $course->title,
            ]
        ],
        'meta' => [
            'total' => 10
        ]
    ]);
});

test('Course list with limit', function () {
    $courses = Course::factory(10)->create();
    $course = $courses->first();
    $response = $this->getJson(route('courses.index', [
        'limit' => 5
    ]));

    $response->assertOk();
    $response->assertJson([
        'data' => [
            [
                'id' => $course->getKey(),
                'title' => $course->title,
            ]
        ],
        'meta' => [
            'total' => 10,
            'per_page' => 5
        ]
    ]);
});

test('Course list with page', function () {
    Course::factory(100)->create();
    $response = $this->getJson(route('courses.index', [
        'page' => 5
    ]));

    $response->assertOk();
    $response->assertJson([
        'meta' => [
            'total' => 100,
            'current_page' => 5
        ]
    ]);
});

test('Course list with search', function () {
    Course::factory(20)->create();

    $courses = Course::factory(10)->create([
        'title' => 'Some '.fake()->name,
    ]);
    $course = $courses->first();

    $response = $this->getJson(route('courses.index', [
        'search' => "some"
    ]));

    $response->assertOk();
    $response->assertJson([
        'data' => [
            [
                'id' => $course->getKey(),
                'title' => $course->title,
            ]
        ],
        'meta' => [
            'total' => 10,
        ]
    ]);
});

test('Create course', function () {
    $response = $this->postJson(route('courses.store'),  [
        'title' => fake()->name,
        'description' => fake()->sentence,
    ]);
    $response->assertOk();
    $this->assertDatabaseCount('courses', 1);
});

test('Create course without description', function () {
    $response = $this->postJson(route('courses.store'),  [
        'title' => fake()->name,
    ]);
    $response->assertOk();
    $this->assertDatabaseCount('courses', 1);
});

test('Course Create fail by invalid description size', function () {
    $response = $this->postJson(route('courses.store'),  [
        'title' => fake()->name,
        'description' => '123',
    ]);
    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor('description');
});

test('Course Create fail by missing title', function () {
    $response = $this->postJson(route('courses.store'),  [
        'description' => fake()->sentence,
    ]);
    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor('title');
});
