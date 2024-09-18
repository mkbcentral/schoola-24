<?php

namespace App\Http\Controllers\Api;

use App\Domain\Features\Configuration\SchoolDataFeature;
use App\Domain\Features\Registration\RegistrationFeature;
use App\Http\Controllers\Controller;
use App\Http\Resources\OptionResource;
use Exception;
use Illuminate\Http\Request;

class StudentCounterController extends Controller
{
    public function countOldAndNewStudent(Request $request)
    {
        try {
            $countNew = RegistrationFeature::getCount(
                null,
                null,
                null,
                null,
                null,
                null,
                false
            );
            $countOld = RegistrationFeature::getCount(
                null,
                null,
                null,
                null,
                null,
                null,
                isOld: true
            );
            return response()->json([
                'count_new' => $countNew,
                'count_old' => $countOld,
                'total' => $countOld + $countNew,
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'error' => $ex->getMessage()
            ]);
        }
    }
    public function countBySection(Request $request)
    {
        try {
            $sections = SchoolDataFeature::getSectionList();
            $sectionCount = [];
            foreach ($sections as $section) {
                $sectionCount[] = [
                    'name' => $section->name,
                    'count' => $section->getRegistrationCountForCurrentSchoolYear(),
                ];
            }
            return response()->json([
                'sections' => $sectionCount
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'error' => $ex->getMessage()
            ]);
        }
    }
    public function countByClasseRoom(Request $request, int $optionId)
    {
        try {
            $classRooms = SchoolDataFeature::getClassRoomList(
                $optionId,
                null,
                null
            );
            $classRoomCount = [];
            foreach ($classRooms as $classRoom) {
                $classRoomCount[] = [
                    'name' => $classRoom->getOriginalClassRoomName(),
                    'count' => $classRoom->getRegistrationCountForCurrentSchoolYear(),
                ];
            }
            return response()->json([
                'classRooms' => $classRoomCount
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'error' => $ex->getMessage()
            ]);
        }
    }
    public function getListOption(Request $request)
    {
        try {
            $options = OptionResource::collection(SchoolDataFeature::getOptionList(100));
            return response()->json([
                'options' => $options
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'error' => $ex->getMessage()
            ]);
        }
    }
}
