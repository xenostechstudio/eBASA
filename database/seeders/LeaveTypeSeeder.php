<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'code' => 'LT-001',
                'name' => 'Cuti Tahunan',
                'description' => 'Cuti tahunan sesuai peraturan ketenagakerjaan',
                'default_days' => 12,
                'is_paid' => true,
                'requires_approval' => true,
                'requires_attachment' => false,
                'is_active' => true,
                'color' => '#3B82F6',
            ],
            [
                'code' => 'LT-002',
                'name' => 'Cuti Sakit',
                'description' => 'Cuti karena sakit dengan surat keterangan dokter',
                'default_days' => 14,
                'is_paid' => true,
                'requires_approval' => true,
                'requires_attachment' => true,
                'is_active' => true,
                'color' => '#EF4444',
            ],
            [
                'code' => 'LT-003',
                'name' => 'Cuti Melahirkan',
                'description' => 'Cuti melahirkan untuk karyawan wanita',
                'default_days' => 90,
                'is_paid' => true,
                'requires_approval' => true,
                'requires_attachment' => true,
                'is_active' => true,
                'color' => '#EC4899',
            ],
            [
                'code' => 'LT-004',
                'name' => 'Cuti Menikah',
                'description' => 'Cuti untuk pernikahan karyawan',
                'default_days' => 3,
                'is_paid' => true,
                'requires_approval' => true,
                'requires_attachment' => true,
                'is_active' => true,
                'color' => '#F59E0B',
            ],
            [
                'code' => 'LT-005',
                'name' => 'Cuti Duka',
                'description' => 'Cuti karena keluarga meninggal dunia',
                'default_days' => 3,
                'is_paid' => true,
                'requires_approval' => true,
                'requires_attachment' => false,
                'is_active' => true,
                'color' => '#6B7280',
            ],
            [
                'code' => 'LT-006',
                'name' => 'Izin Tidak Masuk',
                'description' => 'Izin tidak masuk kerja tanpa potong cuti',
                'default_days' => 0,
                'is_paid' => false,
                'requires_approval' => true,
                'requires_attachment' => false,
                'is_active' => true,
                'color' => '#8B5CF6',
            ],
            [
                'code' => 'LT-007',
                'name' => 'Cuti Besar',
                'description' => 'Cuti besar setelah masa kerja tertentu',
                'default_days' => 30,
                'is_paid' => true,
                'requires_approval' => true,
                'requires_attachment' => false,
                'is_active' => true,
                'color' => '#10B981',
            ],
        ];

        foreach ($types as $type) {
            LeaveType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }
}
