<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    public function run(): void
    {
        $shifts = [
            [
                'code' => 'SH-001',
                'name' => 'Pagi',
                'start_time' => '07:00',
                'end_time' => '15:00',
                'break_start' => '12:00',
                'break_end' => '13:00',
                'break_duration' => 60,
                'working_hours' => 7,
                'is_overnight' => false,
                'is_active' => true,
                'description' => 'Shift pagi standar',
            ],
            [
                'code' => 'SH-002',
                'name' => 'Siang',
                'start_time' => '14:00',
                'end_time' => '22:00',
                'break_start' => '18:00',
                'break_end' => '19:00',
                'break_duration' => 60,
                'working_hours' => 7,
                'is_overnight' => false,
                'is_active' => true,
                'description' => 'Shift siang standar',
            ],
            [
                'code' => 'SH-003',
                'name' => 'Malam',
                'start_time' => '22:00',
                'end_time' => '06:00',
                'break_start' => '02:00',
                'break_end' => '03:00',
                'break_duration' => 60,
                'working_hours' => 7,
                'is_overnight' => true,
                'is_active' => true,
                'description' => 'Shift malam (overnight)',
            ],
            [
                'code' => 'SH-004',
                'name' => 'Office',
                'start_time' => '08:00',
                'end_time' => '17:00',
                'break_start' => '12:00',
                'break_end' => '13:00',
                'break_duration' => 60,
                'working_hours' => 8,
                'is_overnight' => false,
                'is_active' => true,
                'description' => 'Shift kantor standar',
            ],
            [
                'code' => 'SH-005',
                'name' => 'Split Shift',
                'start_time' => '06:00',
                'end_time' => '14:00',
                'break_start' => '10:00',
                'break_end' => '10:30',
                'break_duration' => 30,
                'working_hours' => 7,
                'is_overnight' => false,
                'is_active' => true,
                'description' => 'Shift pagi awal',
            ],
        ];

        foreach ($shifts as $shift) {
            Shift::create($shift);
        }
    }
}
