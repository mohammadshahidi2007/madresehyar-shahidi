<?php

namespace Tests\Feature\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Tests\TestCase;

class SchoolSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_school_core_tables_are_created(): void
    {
        foreach (['academic_years', 'classrooms', 'students', 'subjects', 'classroom_student'] as $table) {
            $this->assertTrue(DB::getSchemaBuilder()->hasTable($table), "Missing table: {$table}");
        }
    }

    public function test_student_can_be_enrolled_in_a_classroom(): void
    {
        $yearId = DB::table('academic_years')->insertGetId([
            'name' => '1405-1406',
            'starts_on' => '2026-09-23',
            'ends_on' => '2027-06-21',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $classroomId = DB::table('classrooms')->insertGetId([
            'academic_year_id' => $yearId,
            'grade' => 7,
            'section' => 'الف',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $studentId = DB::table('students')->insertGetId([
            'student_code' => 'ST-TEST-001',
            'first_name' => 'آزمایشی',
            'last_name' => 'دانش‌آموز',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('classroom_student')->insert([
            'classroom_id' => $classroomId,
            'student_id' => $studentId,
            'enrolled_on' => '2026-09-23',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertDatabaseHas('classroom_student', [
            'classroom_id' => $classroomId,
            'student_id' => $studentId,
            'status' => 'active',
        ]);
    }

    public function test_classroom_section_is_unique_within_grade_and_year(): void
    {
        $yearId = DB::table('academic_years')->insertGetId([
            'name' => '1406-1407',
            'starts_on' => '2027-09-23',
            'ends_on' => '2028-06-21',
            'is_active' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $row = [
            'academic_year_id' => $yearId,
            'grade' => 8,
            'section' => 'ب',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('classrooms')->insert($row);

        $this->expectException(QueryException::class);
        DB::table('classrooms')->insert($row);
    }
}
