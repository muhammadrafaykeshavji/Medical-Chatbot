<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PakistanDoctorSeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            ['name' => 'Karachi', 'lat' => 24.8607, 'lng' => 67.0011, 'state' => 'Sindh'],
            ['name' => 'Lahore', 'lat' => 31.5204, 'lng' => 74.3587, 'state' => 'Punjab'],
            ['name' => 'Islamabad', 'lat' => 33.6844, 'lng' => 73.0479, 'state' => 'Islamabad'],
            ['name' => 'Rawalpindi', 'lat' => 33.5651, 'lng' => 73.0169, 'state' => 'Punjab'],
            ['name' => 'Faisalabad', 'lat' => 31.4180, 'lng' => 73.0791, 'state' => 'Punjab'],
            ['name' => 'Multan', 'lat' => 30.1575, 'lng' => 71.5249, 'state' => 'Punjab'],
            ['name' => 'Peshawar', 'lat' => 34.0151, 'lng' => 71.5249, 'state' => 'Khyber Pakhtunkhwa'],
            ['name' => 'Quetta', 'lat' => 30.1798, 'lng' => 66.9750, 'state' => 'Balochistan'],
            ['name' => 'Hyderabad', 'lat' => 25.3960, 'lng' => 68.3578, 'state' => 'Sindh'],
            ['name' => 'Sialkot', 'lat' => 32.4945, 'lng' => 74.5229, 'state' => 'Punjab'],
        ];

        $specialties = [
            'Cardiology' => 'Heart Disease Treatment, Preventive Cardiology, Cardiac Rehab',
            'Neurology' => 'Stroke, Epilepsy, Neurodegenerative Disorders',
            'Oncology' => 'Chemotherapy, Immunotherapy, Cancer Screening',
            'Dermatology' => 'Skin Cancer Screening, Cosmetic Dermatology',
            'Endocrinology' => 'Diabetes, Thyroid, Hormone Therapy',
            'Gastroenterology' => 'Endoscopy, IBD Treatment, Liver Diseases',
            'Ophthalmology' => 'Eye Infection Treatment, Cataract, Retinal Care',
            'Psychiatry' => 'Anxiety, Depression, Mood Disorders',
            'Pulmonology' => 'Asthma, COPD, Sleep Medicine',
            'Internal Medicine' => 'General Medicine, Preventive Care',
            'Orthopedics' => 'Sports Injuries, Joint Replacement',
            'Pediatrics' => 'Child Health, Immunization, Growth & Development',
            'ENT' => 'Ear, Nose, Throat, Sinus',
            'Urology' => 'Kidney, Prostate, Stone Management',
            'Gynecology' => 'Women Health, Pregnancy Care',
        ];

        $firstNames = ['Ahmed','Ayesha','Ali','Fatima','Hassan','Sara','Usman','Kiran','Bilal','Sana','Zain','Hira','Hamza','Madiha','Taha','Noor','Nida','Imran','Maryam','Ammar','Javeria','Shahzaib','Hafsa','Rida'];
        $lastNames = ['Khan','Ahmed','Hussain','Malik','Sheikh','Qureshi','Raza','Farooq','Javed','Shah','Yousaf','Rehman','Chaudhry','Butt','Rana'];

        $emailIndex = 1;
        foreach ($cities as $city) {
            foreach ($specialties as $spec => $services) {
                // 6 doctors per specialty per city (~900 total for 10x15)
                for ($i = 0; $i < 6; $i++) {
                    $name = $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
                    $qualification = 'MBBS, FCPS';
                    $hospital = $city['name'] . ' ' . $spec . ' Center';
                    $latJitter = ((mt_rand(-700, 700)) / 10000); // ±0.07°
                    $lngJitter = ((mt_rand(-700, 700)) / 10000);

                    Doctor::create([
                        'name' => $name,
                        'specialty' => $spec,
                        'qualification' => $qualification,
                        'hospital_name' => $hospital,
                        'bio' => $spec . ' specialist practicing in ' . $city['name'] . ' focused on evidence-based care.',
                        'phone' => '+92-3' . mt_rand(10, 49) . '-' . mt_rand(1000000, 9999999),
                        'email' => Str::slug($name, '.') . ".{$emailIndex}@example.pk",
                        'website' => null,
                        'address' => 'Main Road, ' . $city['name'],
                        'city' => $city['name'],
                        'state' => $city['state'],
                        'latitude' => round($city['lat'] + $latJitter, 6),
                        'longitude' => round($city['lng'] + $lngJitter, 6),
                        'rating' => round(mt_rand(42, 50) / 10, 2),
                        'years_experience' => mt_rand(3, 30),
                        'available_days' => json_encode(['Monday','Tuesday','Wednesday','Thursday','Friday']),
                        'available_from' => '09:00:00',
                        'available_to' => '17:00:00',
                        'consultation_fee' => mt_rand(1500, 6000) / 10,
                        'services' => $services,
                        'languages' => 'English, Urdu',
                        'accepts_insurance' => true,
                        'insurance_accepted' => json_encode(['State Life','Adamjee','Jubilee']),
                        'image_url' => null,
                        'is_available' => true,
                    ]);
                    $emailIndex++;
                }
            }
        }
    }
}


