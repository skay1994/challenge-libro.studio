<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\{User, Course};

uses(RefreshDatabase::class);

test('Create user', function () {
    $name = fake()->name;
    $response = $this->postJson(route('users.store'), [
        'name' => $name,
        'email' => fake()->unique()->safeEmail(),
        'gender' => 'male',
        'birthday_at' => now()->subYears(20),
    ]);
    $response->assertOk()
        ->assertJson([
            'data' => [
                'name' => $name
            ]
        ]);

    $this->assertDatabaseCount('users', 1);
});

test('Create user with courses', function () {
    $courses = Course::factory(2)->create();
    $course = $courses->first();

    $name = fake()->name;
    $response = $this->postJson(route('users.store'), [
        'name' => $name,
        'email' => fake()->unique()->safeEmail(),
        'gender' => 'male',
        'birthday_at' => now()->subYears(20),
        'course_id' => $courses->pluck('id')
    ]);

    $response->assertOk()
        ->assertJson([
            'data' => [
                'name' => $name,
                'courses' => [
                    [
                        'id' => $course->getKey(),
                        'title' => $course->title,
                    ]
                ]
            ]
        ]);

    $this->assertDatabaseCount('users', 1);
});

test('Create user fail by invalid course', function () {
    $response = $this->postJson(route('users.store'), [
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail(),
        'gender' => 'male',
        'birthday_at' => now()->subYears(20),
        'course_id' => ['99']
    ]);
    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor('course_id.0');
});

test('Create user fail by missing name', function () {
    $response = $this->postJson(route('users.store'), [
        'email' => fake()->unique()->safeEmail(),
        'gender' => 'male',
        'birthday_at' => now()->subYears(20),
    ]);
    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor('name');
});

test('Create user fail by invalid email', function () {
    $response = $this->postJson(route('users.store'), [
        'name' => fake()->name,
        'email' => 'sometexthere',
        'gender' => 'male',
        'birthday_at' => now()->subYears(20),
    ]);
    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor('email');
});

test('Create user fail by missing email', function () {
    $response = $this->postJson(route('users.store'), [
        'name' => fake()->name,
        'gender' => 'male',
        'birthday_at' => now()->subYears(20),
    ]);
    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor('email');
});

test('Create user fail by invalid gender', function () {
    $response = $this->postJson(route('users.store'), [
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail(),
        'gender' => 'othergender',
        'birthday_at' => now()->subYears(20),
    ]);
    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor('gender');
});

test('Create user fail by missing birthday', function () {
    $response = $this->postJson(route('users.store'), [
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail(),
        'gender' => 'male',
    ]);
    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor('birthday_at');
});

test('Create user fail by invalid birthday', function () {
    $response = $this->postJson(route('users.store'), [
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail(),
        'gender' => 'male',
        'birthday_at' => 'birthday',
    ]);
    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor('birthday_at');
});
