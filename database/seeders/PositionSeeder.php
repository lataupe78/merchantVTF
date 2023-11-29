<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [

            [
                'title' => 'Tc',
                'lat' => '48.72546880',
                'lng'     => '2.15549520',
                'altitude'    => '186.79998779',
                'accuracy' =>     '20.85300064',
            ],
            [
                'title' => 'Machine à café',
                'lat' => '48.72541120',
                'lng'     => '2.15506310',
                'altitude'    => '186.89999390',
                'accuracy' =>     '12.07699966',
            ],
            [
                'title' => 'Tourniquet',
                'lat' => '48.72575740',
                'lng'     => '2.15667890',
                'altitude'    => '186.50000000',
                'accuracy' =>     '38.21200180',
            ],
            [
                'title' => 'Boulangerie',
                'lat' => '48.70215740',
                'lng'     => '2.13343630',
                'altitude'    => '112.50000000',
                'accuracy' =>     '13.95899963',
            ],
            [
                'title' => 'Garé devant point de vue à Gif',
                'lat' => '48.70215540',
                'lng'     => '2.13372050',
                'altitude'    => '113.09999847',
                'accuracy' =>     '13.32900047',
            ],
            [
                'title' => 'Devant christ',
                'lat' => '48.73095030',
                'lng'     => '2.16382490',
                'altitude'    => '202.09999084',
                'accuracy' =>     '17.77599907',
            ],
            [
                'title' => 'Test chambre',
                'lat' => '48.77699730',
                'lng'     => '2.06863360',
                'altitude'    => '208.50000000',
                'accuracy' =>     '14.36400032',
            ],


            [
                'title' =>
                'Sdc',    'lat' => '48.72547000',
                'lng' => '2.15546210',
                'altitude' => '186.79998779',
                'accuracy' =>
                12.60400009
            ],

            [
                'title' =>
                'Cour TC Sapin',    'lat' => '48.72543630',
                'lng' => '2.15559020',
                'altitude' => '189.59999084',
                'accuracy' =>
                77.59999847
            ],

            [
                'title' =>
                'Cour TC',    'lat' => '48.72543790',
                'lng' => '2.15555540',
                'altitude' => '186.79998779',
                'accuracy' =>
                72.90000153
            ],
            [
                'title' => 'Cendrier machine à café',
                'lat' => '48.72541350',    'lng' => '2.15507470',    'altitude' => '189.50000000',    'accuracy' => '17.23200035',
            ],
            [
                'title' => 'Maison papa',
                'lat' => '48.71436620',    'lng' => '2.05953170',    'altitude' => '164.69999695',    'accuracy' => '100.00000000',
            ],
            [
                'title' => 'Maison papa',
                'lat' => '48.71436620',    'lng' => '2.05953170',    'altitude' => '164.69999695',    'accuracy' => '100.00000000',
            ],
            [
                'title' => 'Gare du nord',
                'lat' => '48.88020150',    'lng' => '2.35539750',    'altitude' => '102.50000000',    'accuracy' => '21.40500069',
            ],
            [
                'title' => 'Ssg cd',
                'lat' => '48.72548200',    'lng' => '2.15572200',    'altitude' => '186.69999695',    'accuracy' => '24.83200073',
            ],
            [
                'title' => '',
                'lat' => '48.72546750',    'lng' => '2.15577270',    'altitude' => '189.29998779',    'accuracy' => '11.86499977',
            ],
            [
                'title' => 'Sdc',
                'lat' => '48.72547000',    'lng' => '2.15546210',    'altitude' => '186.79998779',    'accuracy' => '12.60400009',
            ],
            [
                'title' => 'Sapin',
                'lat' => '48.72543630',    'lng' => '2.15559020',    'altitude' => '189.59999084',    'accuracy' => '77.59999847',
            ],

        ];

        foreach ($positions as $pos) {

            Position::create([
                'title' => $pos['title'],
                'lat' => $pos['lat'],
                'lng' => $pos['lng'],
                'altitude' => $pos['altitude'],
                'accuracy' => $pos['accuracy'],
            ]);
        }
    }
}
