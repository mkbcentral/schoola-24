<?php

namespace App\Http\Controllers\Api\Student;

use App\Domain\Features\Registration\RegistrationFeature;
use App\Exceptions\CustomExceptionHandler;
use App\Http\Controllers\Controller;
use App\Http\Resources\RegistrationResource;
use Exception;
use Illuminate\Http\Request;

class StudnetController extends Controller
{
    public function getStudents(Request $request)
    {
        $search = $request->input('search');
        try {
            $students = RegistrationFeature::getList(
                null,
                null,
                null,
                null,
                null,
                null,
                $search,
                'students.name',
                true,
                null,
                1000
            );
            return response()->json([
                'students' => RegistrationResource::collection($students)
            ]);
        } catch (Exception $exception) {
            $handler = new CustomExceptionHandler();
            return $handler->render(request(), $exception);
        }
    }
}
