<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'code' => 'PML',
                'name' => 'Pemalang',
                'city' => 'Pemalang',
                'province' => 'Central Java',
                'address' => 'Jl. Pemuda No. 1, Pemalang',
                'phone' => '0284-111111',
                'email' => 'pemalang@basa.test',
                'manager_name' => 'Pemalang Manager',
            ],
            [
                'code' => 'TGL',
                'name' => 'Tegal',
                'city' => 'Tegal',
                'province' => 'Central Java',
                'address' => 'Jl. Pantura No. 10, Tegal',
                'phone' => '0283-222222',
                'email' => 'tegal@basa.test',
                'manager_name' => 'Tegal Manager',
            ],
            [
                'code' => 'BNJ',
                'name' => 'Banjaran',
                'city' => 'Banjaran',
                'province' => 'West Java',
                'address' => 'Jl. Raya Banjaran No. 5, Banjaran',
                'phone' => '022-333333',
                'email' => 'banjaran@basa.test',
                'manager_name' => 'Banjaran Manager',
            ],
        ];

        foreach ($branches as $data) {
            $branch = Branch::updateOrCreate(
                ['code' => $data['code']],
                array_merge($data, [
                    'is_active' => true,
                    'meta' => null,
                ]),
            );

            $warehouseDefinitions = [
                [
                    'code' => $branch->code . '-WH1',
                    'name' => $branch->name . ' Main Warehouse',
                    'suffix' => 'Warehouse 1',
                ],
                [
                    'code' => $branch->code . '-WH2',
                    'name' => $branch->name . ' Secondary Warehouse',
                    'suffix' => 'Warehouse 2',
                ],
            ];

            foreach ($warehouseDefinitions as $wh) {
                Warehouse::updateOrCreate(
                    ['code' => $wh['code']],
                    [
                        'name' => $wh['name'],
                        'branch_id' => $branch->id,
                        'city' => $branch->city,
                        'province' => $branch->province,
                        'address' => $branch->address . ' - ' . $wh['suffix'],
                        'phone' => $branch->phone,
                        'contact_name' => $branch->manager_name,
                        'is_active' => true,
                        'meta' => null,
                    ],
                );
            }
        }
    }
}
