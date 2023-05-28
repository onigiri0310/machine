<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = [
            [
                'company_name' => '〇〇株式会社',
                'street_address' => '〇〇市〇〇町1-2-3',
                'representative_name' => '山田太郎',
            ],
            [
                'company_name' => '△△株式会社',
                'street_address' => '△△市△△町4-5-6',
                'representative_name' => '鈴木花子',
            ],
            // 追加のデータをここに定義する
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}
