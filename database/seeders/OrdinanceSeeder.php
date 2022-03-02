<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdinanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        activity()->disableLogging();
        $faker = \Faker\Factory::create();

        $files = [
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936636/sample/ordinances/no.-15-143_zwfmkn.pdf',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936619/sample/ordinances/no.-13-009-river-rehabilitation-and-protection-council_jfrflh.pdf',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936580/sample/ordinances/no.-17-087_lrcc0k.pdf',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936558/sample/ordinances/no.-19-246_hrodtt.pdf',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936519/sample/ordinances/no.-19-251_ovbyuz.pdf',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936518/sample/ordinances/no.-19-248_ykdyyw.pdf',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936491/sample/ordinances/no.-19-244_ihlwch.pdf',
            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936466/sample/ordinances/blg-10-109_svwqoj.pdf',

            'https://res.cloudinary.com/dtitv38uo/image/upload/v1645936462/sample/ordinances/blg-09-087_nrjvsp.pdf',
        ];

        foreach (range(13,20) as $typeID) {
            $ordinanceCount = $faker->numberBetween(1,2);

            foreach (range(1,$ordinanceCount) as $index) {

                $file_path = $files[array_rand($files)];
                $file_name = 'barangay/'.uniqid().'-'.time();
                $timestamp = $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null);
                DB::table('ordinances')->insert([
                    'ordinance_no' => $faker->numberBetween(07, 21).'-'.$faker->numberBetween(400, 20000),
                    'type_id'=> $typeID,
                    'title' => strtoupper($faker->realText($maxNbChars = 200, $indexSize = 2)),
                    'date_approved' => $faker->date($format = 'Y-m-d', $max = 'now'),
                    'pdf_name' => $file_name,
                    'file_path'=> $file_path,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ]);
            }

        }
        // //1
        // DB::table('ordinances')->insert([
        //     'ordinance_no' => '17-087',
        //     'title' => 'KAUTUSANG PANLLUNGSOD NA INAAMYENDAHAN ANG MGA SUMUSUNOD NA SEKSYON: 6.B, SEKSIYON 6.B I,II, AT III AT SEKSYON 8.4.B I,II O ANG MGA PROBISYONG MAY PENAL/PARUSA NG KAUTUSANG PANLUNGSOD BILANG 04-013 O ANG KAUTUSANG IPINAGBABAWAL ANG SASAKYANG PAMPUBLIKO/PAMPASAHERO O PANSARILI NA NAGBUBUGA NG USOK, MAPUTI O MAITIM MAN SA MGA LANSANGANG SAKOP NG LUNGSOD NG MUNTINLUPA.',
        //     'date_approved' => '2017-05-23',
        //     'ordinance_category_id'=> 1,
        //     'pdf_name' => 'no.-17-087.pdf',
        //     'file_path'=> 'ordinances/no.-17-087.pdf',
        //     'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        //     'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        // ]);

        // DB::table('ordinances')->insert([
        //     'ordinance_no' => '15-143',
        //     'title' => 'PRESCRIBING GUIDELINES AND PROCEDURES ON THE PLANTING, MAINTENANCE AND REMOVAL OF TREES (CUTTING, TREE BALLING, TRIMMING, PRUNING) AND OTHER VEGETATION IN URBAN AREAS',
        //     'date_approved' => '2015-11-03',
        //     'ordinance_category_id'=> 1,
        //     'pdf_name' => 'no.-15-143.pdf',
        //     'file_path'=> 'ordinances/no.-15-143.pdf',
        //     'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        //     'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        // ]);

        // DB::table('ordinances')->insert([
        //     'ordinance_no' => '13-009',
        //     'title' => 'ESTABLISHING THE MUNTINLUPA RIVER REHABILITATION AND PROTECTION COUNCIL (MRRPC) DEFINING ITS DUTIES AND RESPONSIBILITIES',
        //     'date_approved' => '2013-09-23',
        //     'ordinance_category_id'=> 1,
        //     'pdf_name' => 'no.-13-009-river-rehabilitation-and-protection-council',
        //     'file_path'=> 'ordinances/no.-13-009-river-rehabilitation-and-protection-council',
        //     'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        //     'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        // ]);

        // DB::table('ordinances')->insert([
        //     'ordinance_no' => '10-109',
        //     'title' => 'PROHIBITING THE USE OF PLASTIC BAGS ON DRY GOODS, REGULATING ITS UTILIZATION ON WET GOODS AND PROHIBITING THE USE OF STYROFOAM/STYROPHOR IN THE CITY OF MUNTINLUPA.',
        //     'date_approved' => '2010-01-18',
        //     'ordinance_category_id'=> 1,
        //     'pdf_name' => 'blg-10-109.pdf',
        //     'file_path'=> 'ordinances/blg-10-109.pdf',
        //     'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        //     'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        // ]);

        // DB::table('ordinances')->insert([
        //     'ordinance_no' => '09-087',
        //     'title' => 'PRESCRIBING ENVIRONMENTAL PROTECTION INSPECTION FEES FOR ALL INDUSTRIAL, AGRICULTURAL, COMMERCIAL ESTABLISHMENTS AND PRIVATE OUTLETS IN THE CITY OF MUNITNLUPA.',
        //     'date_approved' => '2009-04-13',
        //     'ordinance_category_id'=> 1,
        //     'pdf_name' => 'blg-09-087.pdf',
        //     'file_path'=> 'ordinances/blg-09-087.pdf',
        //     'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        //     'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        // ]);

        // //2

        // DB::table('ordinances')->insert([
        //     'ordinance_no' => '19-251',
        //     'title' => 'KAUTUSANG NA NAGKAKALOOB NG TULONG PINANSIYAL KAY KGG. KON. ALEXANDER B. DIAZ PARA SA KANYANG MEDIKAL NA GASTUSIN SA HALAGANG SAMPUNG LIBONG PISO (PHP10,000.00) BAWAT ISANG KONSEHAL NA MAY KABUUANG HALAGA NA ISANG DAAN AT WALUMPUNG LIBONG PISO (PHP180,000.00) NA KUKUNIN SA 2019 EXECUTIVE BUDGET SA ILALIM NG ANUMANG NALALABING PONDO.',
        //     'date_approved' => '2019-06-03',
        //     'ordinance_category_id'=> 2,
        //     'pdf_name' => 'no.-19-251.pdf',
        //     'file_path' => 'ordinances/no.-19-251.pdf',
        //     'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        //     'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        // ]);

        // DB::table('ordinances')->insert([
        //     'ordinance_no' => '19-248',
        //     'title' => 'AN ORDINANCE APPROVING THE REALIGNMENT OF THE PORTION OF THE UNEXPENDED BALANCE OF THE SPECIAL TRUST FUND COVERING THE YEARS 2013-2017 AMOUNTING TO SEVENTY THREE MILLION SIXTY THOUSAND PESOS (PHP73,060,000.00).',
        //     'date_approved' => '2019-04-22',
        //     'ordinance_category_id'=> 2,
        //     'pdf_name' => 'no.-19-248.pdf',
        //     'file_path' => 'ordinances/no.-19-248.pdf',
        //     'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        //     'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        // ]);

        // DB::table('ordinances')->insert([
        //     'ordinance_no' => '19-246',
        //     'title' => 'AN ORDINANCE APPROPRIATING/ALLOCATING THE AMOUNT OF ONE MILLION ONE HUNDRED TWENTY THREE THOUSAND TWENTY PESOS (PHP1,123,020.00- (1.5%) FOR THE PAYMENT OF THE DOCUMENTARY STAMP TAX (DST) IN THE BUREAU OF INTERNAL REVENUE (BIR) AND SEVEN HUNDRED FORTY EIGHT THOUSAND SIX HUNDRED EIGHTY PESOS (PHP748,680.00-1%) FOR THE PAYMENT OF REGISTRATION FEE IN THE REGISTRY OF DEEDS FOR THE PURCHASE OF THE PARCEL OF LAND COVERED BY TCT. NO. (181024)-082906 WITH AN AREA OF 8,808 SQUARE METERS, MORE OR LESS BY THE CITY GOVERNMENT OF MUNTINLUPA FROM DR. MENZI V. ORELLANA AND MRS. MARIA RHED ORELLANA.BAUYA LOCATED AT ADJACENT TO PNR, BARANGAY PUTATAN, MUNTINLUPA CITY AND SHALL BE TAKEN FROM THE SPECIAL EDUCATIONAL FUND (SEF) 2019 EXECUTIVE BUDGET.',
        //     'date_approved' => '2019-03-13',
        //     'ordinance_category_id'=> 2,
        //     'pdf_name' => 'no.-19-246.pdf',
        //     'file_path' => 'ordinances/no.-19-246.pdf',
        //     'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        //     'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        // ]);

        // DB::table('ordinances')->insert([
        //     'ordinance_no' => '19-244',
        //     'title' => 'AN ORDINANCE GRANTING THE AMOUNT OF FIVE HUNDRED THOUSAND PESOS (PHP500,000.00) AS INITIAL FUNDING FOR THE MUNTTNLUPA CITY SCOUTING COUNCIL (MUNCISCO) AS ASSOCIATE COUNCIL OF THE BOY SCOUT OF THE PHILIPPINES (BSP) WHICH AMOUNT SHALL BE TAKEN FROM THE SPECIAL ACTIVITIES FUND (SAF) OF THE CITY MAYOR.',
        //     'date_approved' => '2019-03-13',
        //     'ordinance_category_id'=> 2,
        //     'pdf_name' => 'no.-19-244.pdf',
        //     'file_path' => 'ordinances/no.-19-244.pdf',
        //     'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        //     'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        // ]);

        // DB::table('ordinances')->insert([
        //     'ordinance_no' => '19-243',
        //     'title' => 'AN ORDINANCE APPROVING THE SUPPLEMENTAL BUDGET AMOUNTING TO ONE HUNDRED SIXTY NINE MILLION ONE HUNDRED SEVENTY TWO THOUSAND ONE HUNDRED TWENTY SEVEN PESOS AND 84/100 (PHP169,172,127.84) TO COVER THE STATUTORY AND CONTRACTUAL OBLIGATIONS CONVERTNG THE FIVE PERCENT (5%) CONTRIBUTION TO METRO MANILA DEVELOPMENT AUTHORITY (MMDA) SHARE WHICH AMOUNT SHALL BE TAKEN FROM THE RETAINED OPERATING EXPENSE UNDER THE GENERAL FUNDS.',
        //     'date_approved' => '2019-03-13',
        //     'ordinance_category_id'=> 2,
        //     'pdf_name' => 'no.-19-243.pdf',
        //     'file_path' => 'ordinances/no.-19-243.pdf',
        //     'created_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        //     'updated_at' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null),
        // ]);
    }
}
