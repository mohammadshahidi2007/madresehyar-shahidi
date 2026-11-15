<?php

namespace Tests\Feature;

use App\Http\Requests\StoreAcademicYearRequest;
use App\Http\Requests\StoreClassroomRequest;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\StoreStudentRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class SchoolDomainValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_write_requests_are_denied_until_role_policies_are_implemented(): void
    {
        $requests = [
            new StoreAcademicYearRequest(),
            new StoreClassroomRequest(),
            new StoreStudentRequest(),
            new StoreEnrollmentRequest(),
        ];

        foreach ($requests as $request) {
            $this->assertFalse($request->authorize());
        }
    }

    public function test_classroom_grade_is_limited_to_seventh_through_ninth(): void
    {
        $yearId = $this->createAcademicYear();

        foreach ([7, 8, 9] as $grade) {
            $validator = Validator::make([
                'academic_year_id' => $yearId,
                'grade' => $grade,
                'section' => 'الف',
            ], (new StoreClassroomRequest())->rules());

            $this->assertTrue($validator->passes(), "Grade {$grade} should be accepted.");
        }

        foreach ([6, 10] as $grade) {
            $validator = Validator::make([
                'academic_year_id' => $yearId,
                'grade' => $grade,
                'section' => 'الف',
            ], (new StoreClassroomRequest())->rules());

            $this->assertTrue($validator->fails(), "Grade {$grade} should be rejected.");
        }
    }

    public function test_academic_year_end_must_be_after_start(): void
    {
        $rules = (new StoreAcademicYearRequest())->rules();

        $valid = Validator::make([
            'name' => '1405-1406',
            'starts_on' => '2026-09-23',
            'ends_on' => '2027-06-21',
        ], $rules);

        $invalid = Validator::make([
            'name' => '1406-1407',
            'starts_on' => '2027-09-23',
            'ends_on' => '2027-09-22',
        ], $rules);

        $this->assertTrue($valid->passes());
        $this->assertTrue($invalid->fails());
    }

    public function test_classroom_section_must_be_unique_within_year_and_grade(): void
    {
        $yearId = $this->createAcademicYear();
        $this->createClassroom($yearId);

        $duplicateData = [
            'academic_year_id' => $yearId,
            'grade' => 7,
            'section' => 'الف',
        ];
        $differentGradeData = [
            'academic_year_id' => $yearId,
            'grade' => 8,
            'section' => 'الف',
        ];

        $duplicate = Validator::make($duplicateData, $this->classroomRules($duplicateData));
        $differentGrade = Validator::make($differentGradeData, $this->classroomRules($differentGradeData));

        $this->assertTrue($duplicate->fails());
        $this->assertTrue($differentGrade->passes());
    }

    public function test_persian_class_section_and_internal_student_code_are_accepted(): void
    {
        $yearId = $this->createAcademicYear();

        $classroomData = [
            'academic_year_id' => $yearId,
            'grade' => 7,
            'section' => 'الف',
        ];
        $classroom = Validator::make($classroomData, $this->classroomRules($classroomData));

        $student = Validator::make([
            'student_code' => 'ST_1405-001',
            'first_name' => 'دانش‌آموز',
            'last_name' => 'آزمایشی',
        ], (new StoreStudentRequest())->rules());

        $this->assertTrue($classroom->passes());
        $this->assertTrue($student->passes());
    }

    public function test_enrollment_leaving_date_cannot_precede_enrollment_date(): void
    {
        $yearId = $this->createAcademicYear();
        $classroomId = $this->createClassroom($yearId);
        $studentId = \DB::table('students')->insertGetId([
            'student_code' => 'ST-VAL-001',
            'first_name' => 'نمونه',
            'last_name' => 'آزمایش',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $validator = Validator::make([
            'classroom_id' => $classroomId,
            'student_id' => $studentId,
            'enrolled_on' => '2026-10-01',
            'left_on' => '2026-09-30',
            'status' => 'active',
        ], (new StoreEnrollmentRequest())->rules());

        $this->assertTrue($validator->fails());
    }

    private function classroomRules(array $data): array
    {
        $request = new StoreClassroomRequest();
        $request->merge($data);

        return $request->rules();
    }

    private function createAcademicYear(): int
    {
        return \DB::table('academic_years')->insertGetId([
            'name' => '1405-1406',
            'starts_on' => '2026-09-23',
            'ends_on' => '2027-06-21',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createClassroom(int $yearId): int
    {
        return \DB::table('classrooms')->insertGetId([
            'academic_year_id' => $yearId,
            'grade' => 7,
            'section' => 'الف',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
