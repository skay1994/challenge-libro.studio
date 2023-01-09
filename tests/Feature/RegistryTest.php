<?php

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('Registry add curse to user', function () {
    $course = Course::factory()->create();
    $users = User::factory(3)->create();

    $response = $this->postJson(route('registries.add'), [
        'course_id' => $course->getKey(),
        'user_id' => $users->pluck('id')
    ]);

    $response->assertOk();
    $this->assertDatabaseCount('course_user', 3);
});

test('Registry remove user to curse ', function () {
    $course = Course::factory()->create();
    $users = User::factory(3)->create();

    $course->users()->sync($users->pluck('id'));

    $response = $this->postJson(route('registries.remove'), [
        'course_id' => $course->getKey(),
        'user_id' => $users->pluck('id')
    ]);

    $response->assertOk();
    $this->assertDatabaseCount('course_user', 0);
});
