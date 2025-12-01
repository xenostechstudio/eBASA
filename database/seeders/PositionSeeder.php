<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all();

        if ($departments->isEmpty()) {
            $departments = Department::factory()->count(5)->create();
        }

        $positionProfiles = [
            'OPS' => [
                [
                    'code_suffix' => 'MGR',
                    'title' => 'Kepala Toko',
                    'level' => 'M2',
                    'job_family' => 'Operasional Toko',
                    'is_people_manager' => true,
                    'description' => 'Memimpin operasional harian toko dan tim frontliner.',
                ],
                [
                    'code_suffix' => 'SPV',
                    'title' => 'Supervisor Toko',
                    'level' => 'M1',
                    'job_family' => 'Operasional Toko',
                    'is_people_manager' => true,
                    'description' => 'Mengawasi shift harian, stok display, dan pelayanan pelanggan.',
                ],
                [
                    'code_suffix' => 'CSH',
                    'title' => 'Kasir',
                    'level' => 'P1',
                    'job_family' => 'Operasional Toko',
                    'is_people_manager' => false,
                    'description' => 'Menangani transaksi penjualan dan kas harian.',
                ],
                [
                    'code_suffix' => 'STF',
                    'title' => 'Pramuniaga',
                    'level' => 'P1',
                    'job_family' => 'Operasional Toko',
                    'is_people_manager' => false,
                    'description' => 'Membantu pelanggan dan menjaga kerapian area display.',
                ],
            ],
            'FIN' => [
                [
                    'code_suffix' => 'FNM',
                    'title' => 'Manager Keuangan',
                    'level' => 'M2',
                    'job_family' => 'Keuangan',
                    'is_people_manager' => true,
                    'description' => 'Mengelola laporan keuangan cabang dan kontrol biaya.',
                ],
                [
                    'code_suffix' => 'ACC',
                    'title' => 'Staf Akunting',
                    'level' => 'P2',
                    'job_family' => 'Keuangan',
                    'is_people_manager' => false,
                    'description' => 'Mencatat transaksi akuntansi dan rekonsiliasi kas/bank.',
                ],
                [
                    'code_suffix' => 'CST',
                    'title' => 'Staf Kas & Bank',
                    'level' => 'P1',
                    'job_family' => 'Keuangan',
                    'is_people_manager' => false,
                    'description' => 'Mengelola penerimaan dan pengeluaran kas harian.',
                ],
            ],
            'HRD' => [
                [
                    'code_suffix' => 'HRM',
                    'title' => 'HR Manager',
                    'level' => 'M2',
                    'job_family' => 'SDM',
                    'is_people_manager' => true,
                    'description' => 'Mengelola fungsi HR di cabang: rekrutmen, training, dan people operations.',
                ],
                [
                    'code_suffix' => 'HRG',
                    'title' => 'HR Generalist',
                    'level' => 'P2',
                    'job_family' => 'SDM',
                    'is_people_manager' => false,
                    'description' => 'Menangani administrasi HR, absensi, dan proses onboarding.',
                ],
                [
                    'code_suffix' => 'REC',
                    'title' => 'Recruiter',
                    'level' => 'P1',
                    'job_family' => 'SDM',
                    'is_people_manager' => false,
                    'description' => 'Melakukan sourcing dan screening kandidat untuk kebutuhan cabang.',
                ],
            ],
            'SCM' => [
                [
                    'code_suffix' => 'SCM',
                    'title' => 'Supervisor Gudang',
                    'level' => 'M1',
                    'job_family' => 'Supply Chain & Gudang',
                    'is_people_manager' => true,
                    'description' => 'Mengawasi penerimaan, penyimpanan, dan pengiriman barang.',
                ],
                [
                    'code_suffix' => 'WHK',
                    'title' => 'Koordinator Gudang',
                    'level' => 'P2',
                    'job_family' => 'Supply Chain & Gudang',
                    'is_people_manager' => true,
                    'description' => 'Mengatur layout gudang dan tim picker/packer.',
                ],
                [
                    'code_suffix' => 'WHM',
                    'title' => 'Staf Gudang',
                    'level' => 'P1',
                    'job_family' => 'Supply Chain & Gudang',
                    'is_people_manager' => false,
                    'description' => 'Melakukan proses picking, packing, dan stock opname.',
                ],
                [
                    'code_suffix' => 'DRI',
                    'title' => 'Driver',
                    'level' => 'P1',
                    'job_family' => 'Supply Chain & Gudang',
                    'is_people_manager' => false,
                    'description' => 'Mengantarkan barang ke toko atau pelanggan sesuai jadwal.',
                ],
            ],
            'GEN' => [
                [
                    'code_suffix' => 'STF',
                    'title' => 'Staf',
                    'level' => 'P1',
                    'job_family' => 'Umum',
                    'is_people_manager' => false,
                    'description' => 'Peran staf umum untuk mendukung operasional cabang.',
                ],
            ],
        ];

        foreach ($departments as $department) {
            $suffix = substr($department->code, -3);
            $profiles = $positionProfiles[$suffix] ?? $positionProfiles['GEN'];

            foreach ($profiles as $profile) {
                $code = $department->code . '-' . $profile['code_suffix'];

                Position::updateOrCreate(
                    ['code' => $code],
                    [
                        'title' => $profile['title'],
                        'level' => $profile['level'],
                        'job_family' => $profile['job_family'],
                        'is_people_manager' => $profile['is_people_manager'],
                        'department_id' => $department->id,
                        'branch_id' => $department->branch_id,
                        'description' => $profile['description'],
                        'meta' => null,
                    ]
                );
            }
        }
    }
}
