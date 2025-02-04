<?php

namespace App\Http\Controllers\Api\School;

use App\Domain\Features\Configuration\SchoolDataFeature;
use App\Exceptions\CustomExceptionHandler;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClassRoomResource;
use App\Http\Resources\OptionResource;
use App\Models\ClassRoom;
use App\Models\Option;
use App\Models\School;
use Exception;
use Illuminate\Http\Request;

class SchoolDataController extends Controller
{
    public function getOptionList(Request $request)
    {
        try {
            $options = SchoolDataFeature::getOptionList(20);
            return response()->json([
                'options' => OptionResource::collection($options),
            ]);
        } catch (Exception $exception) {
            $handler = new CustomExceptionHandler();
            return $handler->render(request(), $exception);
        }
    }

    public function getClassRoomListByOption(Request $request, Option $option)
    {
        try {
            $classRooms = SchoolDataFeature::getClassRoomList(
                $option->id,
                null,
                null
            );
            return response()->json([
                'classRooms' => ClassRoomResource::collection($classRooms),
            ]);
        } catch (Exception $exception) {
            $handler = new CustomExceptionHandler();
            return $handler->render(request(), $exception);
        }
    }
}
