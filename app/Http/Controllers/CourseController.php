<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::all();
        return $this->successResponse($courses, 'Courses retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        $validated = $request->validated();
        $course = Course::create($validated);
        return $this->successResponse($course, 'Course created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return $this->successResponse($course, 'Course retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $validated = $request->validated();
        $course->update($validated);
        return $this->successResponse($course, 'Course updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return $this->successResponse(null, 'Course deleted successfully', 204);
    }

    /**
     * Get users enrolled in a course.
     */
    public function enrolledUsers(Course $course)
    {
        $users = $course->users()->get();
        return $this->successResponse($users, 'Enrolled users retrieved successfully');
    }

    /**
     * Enroll a user in a course.
     */
    public function enroll($courseId)
    {
        $user = auth()->user();
        $course = Course::findOrFail($courseId);

        // Check if already enrolled
        if ($user->courses()->where('course_id', $courseId)->exists()) {
            return $this->errorResponse('Already enrolled in this course', 409);
        }

        $user->courses()->attach($courseId);
        return $this->successResponse(null, 'Enrolled successfully', 201);
    }

    /**
     * Unenroll a user from a course.
     */
    public function unenroll($courseId)
    {
        $user = auth()->user();
        $user->courses()->detach($courseId);
        return $this->successResponse(null, 'Unenrolled successfully', 204);
    }
}
