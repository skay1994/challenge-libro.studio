<?php

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('Course list all', function () {
    $courses = Course::factory(10)->create();
    $course = $courses->first();
    $response = $this->getJson(route('courses.index'));

    $response->assertStatus(200);
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

    $response->assertStatus(200);
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

    $response->assertStatus(200);
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

    $response->assertStatus(200);
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
