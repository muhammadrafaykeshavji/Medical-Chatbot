<?php

namespace Database\Seeders;

use App\Models\Doctor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Clear existing doctors
        Doctor::truncate();
        
        $doctors = [
            [
                'name' => 'Sarah Johnson',
                'specialty' => 'Cardiology',
                'qualification' => 'MD, FACC',
                'hospital_name' => 'NYC Medical Center',
                'bio' => 'Experienced cardiologist with 15 years of practice specializing in heart disease prevention and treatment.',
                'phone' => '+1-555-0101',
                'email' => 'sarah.johnson@medicalai.com',
                'website' => 'https://nycmedical.com/sarah-johnson',
                'address' => '123 Medical Center Dr',
                'city' => 'New York',
                'state' => 'NY',
                'latitude' => 40.7589,
                'longitude' => -73.9851,
                'rating' => 4.8,
                'years_experience' => 15,
                'available_days' => json_encode(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']),
                'available_from' => '09:00:00',
                'available_to' => '17:00:00',
                'consultation_fee' => 250.00,
                'services' => 'Heart Disease Treatment, Preventive Cardiology, Cardiac Rehabilitation',
                'languages' => 'English, Spanish',
                'accepts_insurance' => true,
                'insurance_accepted' => json_encode(['Blue Cross', 'Aetna', 'Cigna', 'United Healthcare']),
                'is_available' => true,
            ],
            [
                'name' => 'Michael Chen',
                'specialty' => 'Neurology',
                'qualification' => 'MD, PhD',
                'bio' => 'Leading neurologist specializing in brain disorders and neurological conditions.',
                'phone' => '+1-555-0102',
                'email' => 'michael.chen@medicalai.com',
                'address' => '456 Brain Institute Ave',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'rating' => 4.9,
                'years_experience' => 20,
                'available_days' => json_encode(['Monday', 'Wednesday', 'Friday']),
                'available_from' => '08:00:00',
                'available_to' => '16:00:00',
                'consultation_fee' => 300.00,
                'is_available' => true,
            ],
            [
                'name' => 'Emily Rodriguez',
                'specialty' => 'Pediatrics',
                'qualification' => 'MD, FAAP',
                'bio' => 'Dedicated pediatrician providing comprehensive care for children from infancy through adolescence.',
                'phone' => '+1-555-0103',
                'email' => 'emily.rodriguez@medicalai.com',
                'address' => '789 Children\'s Hospital Rd',
                'city' => 'Chicago',
                'state' => 'IL',
                'rating' => 4.7,
                'years_experience' => 12,
                'available_days' => json_encode(['Tuesday', 'Thursday', 'Saturday']),
                'available_from' => '10:00:00',
                'available_to' => '18:00:00',
                'consultation_fee' => 180.00,
                'is_available' => true,
            ],
            [
                'name' => 'David Wilson',
                'specialty' => 'Orthopedics',
                'qualification' => 'MD, FAAOS',
                'bio' => 'Orthopedic surgeon specializing in sports medicine and joint replacement surgery.',
                'phone' => '+1-555-0104',
                'email' => 'david.wilson@medicalai.com',
                'address' => '321 Sports Medicine Center',
                'city' => 'Miami',
                'state' => 'FL',
                'rating' => 4.6,
                'years_experience' => 18,
                'available_days' => json_encode(['Monday', 'Tuesday', 'Thursday', 'Friday']),
                'available_from' => '07:00:00',
                'available_to' => '15:00:00',
                'consultation_fee' => 280.00,
                'is_available' => true,
            ],
            [
                'name' => 'Lisa Thompson',
                'specialty' => 'Dermatology',
                'qualification' => 'MD, FAAD',
                'bio' => 'Board-certified dermatologist specializing in skin cancer detection and cosmetic dermatology.',
                'phone' => '+1-555-0105',
                'email' => 'lisa.thompson@medicalai.com',
                'address' => '654 Skin Care Clinic Blvd',
                'city' => 'Seattle',
                'state' => 'WA',
                'rating' => 4.5,
                'years_experience' => 10,
                'available_days' => json_encode(['Wednesday', 'Thursday', 'Friday', 'Saturday']),
                'available_from' => '09:00:00',
                'available_to' => '17:00:00',
                'consultation_fee' => 220.00,
                'is_available' => true,
            ],
            [
                'name' => 'Robert Garcia',
                'specialty' => 'Internal Medicine',
                'qualification' => 'MD, FACP',
                'bio' => 'Internal medicine physician providing comprehensive primary care for adults.',
                'phone' => '+1-555-0106',
                'email' => 'robert.garcia@medicalai.com',
                'address' => '987 Primary Care Center',
                'city' => 'Houston',
                'state' => 'TX',
                'rating' => 4.4,
                'years_experience' => 14,
                'available_days' => json_encode(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']),
                'available_from' => '08:30:00',
                'available_to' => '16:30:00',
                'consultation_fee' => 200.00,
                'is_available' => true,
            ],
        ];

        // Add comprehensive doctor data
        $this->addComprehensiveDoctors();
        
        foreach ($doctors as $doctor) {
            Doctor::create($doctor);
        }
    }
    
    private function addComprehensiveDoctors()
    {
        $comprehensiveDoctors = [
            // Cardiology
            ['name' => 'James Mitchell', 'specialty' => 'Cardiology', 'qualification' => 'MD, FACC', 'hospital_name' => 'Heart Institute', 'city' => 'Boston', 'state' => 'MA', 'latitude' => 42.3601, 'longitude' => -71.0589, 'rating' => 4.9, 'years_experience' => 22, 'consultation_fee' => 275.00, 'phone' => '+1-617-555-0201', 'email' => 'james.mitchell@heartinstitute.com', 'address' => '100 Cardiac Way', 'bio' => 'Leading interventional cardiologist specializing in complex cardiac procedures.', 'services' => 'Angioplasty, Cardiac Catheterization, Heart Surgery', 'languages' => 'English, Spanish', 'accepts_insurance' => true, 'insurance_accepted' => json_encode(['Blue Cross', 'Aetna', 'Cigna']), 'available_days' => json_encode(['Monday', 'Tuesday', 'Wednesday', 'Thursday']), 'available_from' => '08:00:00', 'available_to' => '16:00:00', 'is_available' => true],
            
            // Neurology
            ['name' => 'Patricia Wong', 'specialty' => 'Neurology', 'qualification' => 'MD, PhD, FAAN', 'hospital_name' => 'Brain & Spine Center', 'city' => 'San Francisco', 'state' => 'CA', 'latitude' => 37.7749, 'longitude' => -122.4194, 'rating' => 4.8, 'years_experience' => 18, 'consultation_fee' => 320.00, 'phone' => '+1-415-555-0301', 'email' => 'patricia.wong@brainspine.com', 'address' => '200 Neurology Blvd', 'bio' => 'Expert in treating epilepsy, stroke, and neurodegenerative diseases.', 'services' => 'EEG, EMG, Stroke Treatment, Epilepsy Management', 'languages' => 'English, Mandarin', 'accepts_insurance' => true, 'insurance_accepted' => json_encode(['Kaiser', 'Blue Shield', 'United Healthcare']), 'available_days' => json_encode(['Monday', 'Wednesday', 'Friday']), 'available_from' => '09:00:00', 'available_to' => '17:00:00', 'is_available' => true],
            
            // Oncology
            ['name' => 'Robert Kim', 'specialty' => 'Oncology', 'qualification' => 'MD, FACP', 'hospital_name' => 'Cancer Treatment Center', 'city' => 'Atlanta', 'state' => 'GA', 'latitude' => 33.7490, 'longitude' => -84.3880, 'rating' => 4.7, 'years_experience' => 16, 'consultation_fee' => 350.00, 'phone' => '+1-404-555-0401', 'email' => 'robert.kim@cancercenter.com', 'address' => '300 Oncology Drive', 'bio' => 'Medical oncologist specializing in breast and lung cancer treatment.', 'services' => 'Chemotherapy, Immunotherapy, Clinical Trials', 'languages' => 'English, Korean', 'accepts_insurance' => true, 'insurance_accepted' => json_encode(['Anthem', 'Humana', 'Medicare']), 'available_days' => json_encode(['Tuesday', 'Wednesday', 'Thursday', 'Friday']), 'available_from' => '08:30:00', 'available_to' => '16:30:00', 'is_available' => true],
            
            // Dermatology
            ['name' => 'Maria Gonzalez', 'specialty' => 'Dermatology', 'qualification' => 'MD, FAAD', 'hospital_name' => 'Skin Health Institute', 'city' => 'Phoenix', 'state' => 'AZ', 'latitude' => 33.4484, 'longitude' => -112.0740, 'rating' => 4.6, 'years_experience' => 14, 'consultation_fee' => 240.00, 'phone' => '+1-602-555-0501', 'email' => 'maria.gonzalez@skinhealth.com', 'address' => '400 Dermatology Lane', 'bio' => 'Board-certified dermatologist specializing in medical and cosmetic dermatology.', 'services' => 'Skin Cancer Screening, Botox, Laser Treatments', 'languages' => 'English, Spanish', 'accepts_insurance' => true, 'insurance_accepted' => json_encode(['Blue Cross', 'Aetna', 'United Healthcare']), 'available_days' => json_encode(['Monday', 'Tuesday', 'Thursday', 'Friday']), 'available_from' => '09:00:00', 'available_to' => '17:00:00', 'is_available' => true],
            
            // Endocrinology
            ['name' => 'David Patel', 'specialty' => 'Endocrinology', 'qualification' => 'MD, FACE', 'hospital_name' => 'Diabetes & Hormone Center', 'city' => 'Denver', 'state' => 'CO', 'latitude' => 39.7392, 'longitude' => -104.9903, 'rating' => 4.8, 'years_experience' => 19, 'consultation_fee' => 290.00, 'phone' => '+1-303-555-0601', 'email' => 'david.patel@diabetescenter.com', 'address' => '500 Endocrine Way', 'bio' => 'Endocrinologist specializing in diabetes, thyroid disorders, and hormone imbalances.', 'services' => 'Diabetes Management, Thyroid Treatment, Hormone Therapy', 'languages' => 'English, Hindi, Gujarati', 'accepts_insurance' => true, 'insurance_accepted' => json_encode(['Kaiser', 'Cigna', 'Medicare']), 'available_days' => json_encode(['Monday', 'Wednesday', 'Thursday', 'Friday']), 'available_from' => '08:00:00', 'available_to' => '16:00:00', 'is_available' => true],
            
            // Gastroenterology
            ['name' => 'Jennifer Lee', 'specialty' => 'Gastroenterology', 'qualification' => 'MD, FACG', 'hospital_name' => 'Digestive Health Clinic', 'city' => 'Portland', 'state' => 'OR', 'latitude' => 45.5152, 'longitude' => -122.6784, 'rating' => 4.5, 'years_experience' => 13, 'consultation_fee' => 260.00, 'phone' => '+1-503-555-0701', 'email' => 'jennifer.lee@digestivehealth.com', 'address' => '600 GI Boulevard', 'bio' => 'Gastroenterologist specializing in digestive disorders and liver diseases.', 'services' => 'Colonoscopy, Endoscopy, IBD Treatment', 'languages' => 'English, Korean', 'accepts_insurance' => true, 'insurance_accepted' => json_encode(['Blue Shield', 'Humana', 'Aetna']), 'available_days' => json_encode(['Tuesday', 'Wednesday', 'Thursday', 'Saturday']), 'available_from' => '09:00:00', 'available_to' => '17:00:00', 'is_available' => true],
            
            // Ophthalmology
            ['name' => 'Thomas Anderson', 'specialty' => 'Ophthalmology', 'qualification' => 'MD, FACS', 'hospital_name' => 'Vision Care Center', 'city' => 'Nashville', 'state' => 'TN', 'latitude' => 36.1627, 'longitude' => -86.7816, 'rating' => 4.9, 'years_experience' => 21, 'consultation_fee' => 280.00, 'phone' => '+1-615-555-0801', 'email' => 'thomas.anderson@visioncare.com', 'address' => '700 Eye Care Drive', 'bio' => 'Ophthalmologist and eye surgeon specializing in cataract and retinal surgery.', 'services' => 'Cataract Surgery, Retinal Treatment, LASIK', 'languages' => 'English', 'accepts_insurance' => true, 'insurance_accepted' => json_encode(['Anthem', 'Blue Cross', 'Medicare']), 'available_days' => json_encode(['Monday', 'Tuesday', 'Wednesday', 'Friday']), 'available_from' => '08:30:00', 'available_to' => '16:30:00', 'is_available' => true],
            
            // Psychiatry
            ['name' => 'Rachel Williams', 'specialty' => 'Psychiatry', 'qualification' => 'MD, MRCPsych', 'hospital_name' => 'Mental Health Institute', 'city' => 'Austin', 'state' => 'TX', 'latitude' => 30.2672, 'longitude' => -97.7431, 'rating' => 4.7, 'years_experience' => 15, 'consultation_fee' => 220.00, 'phone' => '+1-512-555-0901', 'email' => 'rachel.williams@mentalhealth.com', 'address' => '800 Mental Health Blvd', 'bio' => 'Psychiatrist specializing in anxiety, depression, and mood disorders.', 'services' => 'Therapy, Medication Management, Crisis Intervention', 'languages' => 'English, French', 'accepts_insurance' => true, 'insurance_accepted' => json_encode(['United Healthcare', 'Cigna', 'Aetna']), 'available_days' => json_encode(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']), 'available_from' => '10:00:00', 'available_to' => '18:00:00', 'is_available' => true],
            
            // Pulmonology
            ['name' => 'Kevin Zhang', 'specialty' => 'Pulmonology', 'qualification' => 'MD, FCCP', 'hospital_name' => 'Respiratory Care Center', 'city' => 'Minneapolis', 'state' => 'MN', 'latitude' => 44.9778, 'longitude' => -93.2650, 'rating' => 4.6, 'years_experience' => 17, 'consultation_fee' => 270.00, 'phone' => '+1-612-555-1001', 'email' => 'kevin.zhang@respiratorycare.com', 'address' => '900 Lung Health Ave', 'bio' => 'Pulmonologist specializing in asthma, COPD, and sleep disorders.', 'services' => 'Pulmonary Function Tests, Sleep Studies, Bronchoscopy', 'languages' => 'English, Mandarin', 'accepts_insurance' => true, 'insurance_accepted' => json_encode(['Blue Cross', 'Humana', 'Medicare']), 'available_days' => json_encode(['Monday', 'Wednesday', 'Thursday', 'Friday']), 'available_from' => '08:00:00', 'available_to' => '16:00:00', 'is_available' => true],
            
            // Add some doctors in Pakistan/Karachi area for testing
            ['name' => 'Ahmed Hassan', 'specialty' => 'Ophthalmology', 'qualification' => 'MBBS, FCPS', 'hospital_name' => 'Karachi Eye Hospital', 'city' => 'Karachi', 'state' => 'Sindh', 'latitude' => 24.8607, 'longitude' => 67.0011, 'rating' => 4.8, 'years_experience' => 15, 'consultation_fee' => 150.00, 'phone' => '+92-21-555-0101', 'email' => 'ahmed.hassan@karachieye.com', 'address' => 'Clifton Block 5', 'bio' => 'Leading ophthalmologist specializing in eye infections, cataracts, and retinal disorders.', 'services' => 'Eye Infection Treatment, Cataract Surgery, Retinal Treatment', 'languages' => 'English, Urdu', 'accepts_insurance' => true, 'insurance_accepted' => json_encode(['State Life', 'Adamjee', 'EFU']), 'available_days' => json_encode(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']), 'available_from' => '09:00:00', 'available_to' => '17:00:00', 'is_available' => true],
            
            ['name' => 'Fatima Khan', 'specialty' => 'Internal Medicine', 'qualification' => 'MBBS, FCPS', 'hospital_name' => 'Aga Khan Hospital', 'city' => 'Karachi', 'state' => 'Sindh', 'latitude' => 24.8615, 'longitude' => 67.0099, 'rating' => 4.7, 'years_experience' => 12, 'consultation_fee' => 120.00, 'phone' => '+92-21-555-0102', 'email' => 'fatima.khan@aku.edu', 'address' => 'Stadium Road', 'bio' => 'Internal medicine specialist treating infections, fever, and general health conditions.', 'services' => 'Infection Treatment, General Medicine, Preventive Care', 'languages' => 'English, Urdu, Sindhi', 'accepts_insurance' => true, 'insurance_accepted' => json_encode(['State Life', 'Jubilee', 'TPL']), 'available_days' => json_encode(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Saturday']), 'available_from' => '08:00:00', 'available_to' => '16:00:00', 'is_available' => true],
        ];
        
        foreach ($comprehensiveDoctors as $doctor) {
            Doctor::create($doctor);
        }
    }
}
