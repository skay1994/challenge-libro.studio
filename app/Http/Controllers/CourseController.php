<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseResource;
use Illuminate\Http\Request;

use App\Repositories\CourseRepository;

use App\Http\Requests\StoreCourseRequest;

use App\Models\Course;

class CourseController extends Controller
{
    public function __construct(private CourseRepository $repository)
    {
    }

    public function index(Request $request)
    {
        $data = $request->only(['limit', 'search']);
        return $this->repository->getAll($data);
    }

    public function store(StoreCourseRequest $request)
    {
        $data = $request->safe()->all();
        return $this->repository->store($data);
    }

    public function show(Course $course)
    {
        return new CourseResource($course);
    }

    public function update(StoreCourseRequest $request, Course $course)
    {
        $data = $request->safe()->all();
        return $this->repository->update($course, $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        //
    }
}