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

test('Update user', function () {
    $user = User::factory()->create();
    $name = fake()->name;
    $response = $this->putJson(route('users.update', [
        'user' => $user->getKey()
    ]), [
        'name' => $name,
        'email' => $user->email,
        'gender' => $user->gender,
        'birthday_at' => $user->birthday_at,
    ]);
    $response->assertOk()
        ->assertJson([
            'data' => [
                'name' => $name
            ]
        ]);

    $this->assertDatabaseCount('users', 1);
    $this->assertDatabaseHas('users', [
        'name' => $name,
    ]);
});

test('Update user with courses', function () {
    $courses = Course::factory(2)->create();
    $course = $courses->first();

    $user = User::factory()->create();
    $name = fake()->name;
    $response = $this->putJson(route('users.update', [
        'user' => $user->getKey()
    ]), [
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

test('Update user with new courses', function () {
    $oldCourse = Course::factory()->create();
    $courses = Course::factory(2)->create();
    $course = $courses->first();

    $user = User::factory()->create();
    $user->courses()->sync([$oldCourse->getKey()]);

    $name = fake()->name;
    $response = $this->putJson(route('users.update', [
        'user' => $user->getKey()
    ]), [
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
    $this->assertDatabaseMissing('course_user', [
        'user_id' => $user->getKey(),
        'course_id' => $oldCourse->getKey()
    ]);
});

test('Update user fail by invalid course', function () {
    $user = User::factory()->create();
    $response = $this->putJson(route('users.update', [
        'user' => $user->getKey()
    ]), [
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail(),
        'gender' => 'male',
        'birthday_at' => now()->subYears(20),
        'course_id' => ['99']
    ]);
    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor('course_id.0');
});

test('Update user fail by missing name', function () {
    $user = User::factory()->create();
    $response = $this->putJson(route('users.update', [
        'user' => $user->getKey()
    ]), [
        'email' => fake()->unique()->safeEmail(),
        'gender' => 'male',
        'birthday_at' => now()->subYears(20),
    ]);
    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor('name');
});

test('Update user fail by invalid email', function () {
    $user = User::factory()->create();
    $response = $this->putJson(route('users.update', [
        'user' => $user->getKey()
    ]), [
        'name' => fake()->name,
        'email' => 'sometexthere',
        'gender' => 'male',
        'birthday_at' => now()->subYears(20),
    ]);
    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor('email');
});

test('Update user fail by missing email', function () {
    $user = User::factory()->create();
    $response = $this->putJson(route('users.update', [
        'user' => $user->getKey()
    ]), [
        'name' => fake()->name,
        'gender' => 'male',
        'birthday_at' => now()->subYears(20),
    ]);
    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor('email');
});

test('Update user fail by invalid gender', function () {
    $user = User::factory()->create();
    $response = $this->putJson(route('users.update', [
        'user' => $user->getKey()
    ]), [
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail(),
        'gender' => 'othergender',
        'birthday_at' => now()->subYears(20),
    ]);
    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor('gender');
});

test('Update user fail by missing birthday', function () {
    $user = User::factory()->create();
    $response = $this->putJson(route('users.update', [
        'user' => $user->getKey()
    ]), [
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail(),
        'gender' => 'male',
    ]);
    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor('birthday_at');
});

test('Update user fail by invalid birthday', function () {
    $user = User::factory()->create();
    $response = $this->putJson(route('users.update', [
        'user' => $user->getKey()
    ]), [
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail(),
        'gender' => 'male',
        'birthday_at' => 'birthday',
    ]);
    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor('birthday_at');
});
