<?php

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('List all', function () {
    $users = User::factory(10)->create();
    $user = $users->first();
    $response = $this->getJson(route('users.index'));

    $response->assertOk();
    $response->assertJson([
        'data' => [
            [
                'id' => $user->getKey(),
                'name' => $user->name,
                'email' => $user->email,
            ]
        ],
        'meta' => [
            'total' => 10
        ]
    ]);
});

test('List with limit', function () {
    $users = User::factory(10)->create();
    $user = $users->first();
    $response = $this->getJson(route('users.index', [
        'limit' => 5
    ]));

    $response->assertOk();
    $response->assertJson([
        'data' => [
            [
                'id' => $user->getKey(),
                'name' => $user->name,
                'email' => $user->email,
            ]
        ],
        'meta' => [
            'total' => 10,
            'per_page' => 5
        ]
    ]);
});

test('List with page', function () {
    User::factory(100)->create();
    $response = $this->getJson(route('users.index', [
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

test('List with search by name', function () {
    User::factory(20)->create();

    $users = User::factory(10)->create([
        'name' => 'Some ' . fake()->name,
    ]);
    $user = $users->first();

    $response = $this->getJson(route('users.index', [
        'search' => "some"
    ]));
    $response->assertOk();
    $response->assertJson([
        'data' => [
            [
                'id' => $user->getKey(),
                'name' => $user->name,
                'email' => $user->email,
            ]
        ],
        'meta' => [
            'total' => 10,
        ]
    ]);
});

test('List with search by email', function () {
    User::factory(20)->create();
    $email = "some_email";

    $user = User::factory()->create([
        'email' => "$email@email.com.br"
    ]);
    User::factory()->create([
        'email' => "$email@email.com"
    ]);

    $response = $this->getJson(route('users.index', [
        'search' => $email
    ]));
    $response->assertOk();
    $response->assertJson([
        'data' => [
            [
                'id' => $user->getKey(),
                'name' => $user->name,
                'email' => $user->email,
            ]
        ],
        'meta' => [
            'total' => 2,
        ]
    ]);
});

test('Delete Course', function () {
    $user = User::factory()->create();

    $response = $this->deleteJson(route('users.destroy', ['user' => $user->getKey()]));
    $response->assertOk();

    $this->assertDatabaseCount('users', 0);
});

test('Delete Course with course', function () {
    $course = Course::factory()->create();
    $user = User::factory()->create();
    $user->courses()->sync([$course->getKey()]);

    $response = $this->deleteJson(route('users.destroy', ['user' => $user->getKey()]));
    $response->assertOk();

    $this->assertDatabaseCount('users', 0);
    $this->assertDatabaseMissing('course_user', [
        'user_id' => $user->getKey(),
        'course_id' => $course->getKey()
    ]);
});

test('Delete User with error by not found', function () {
    $response = $this->deleteJson(route('users.destroy', ['user' => 99]));
    $response->assertNotFound();
});
