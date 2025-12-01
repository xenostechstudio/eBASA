<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    protected static array $usedNames = [];
    protected static int $nameIndex = 0;

    protected static array $firstNamesMale = [
        'Budi', 'Andi', 'Rizky', 'Agus', 'Fajar', 'Dimas', 'Hendra', 'Wahyu', 'Eko', 'Bambang',
        'Arif', 'Yusuf', 'Ahmad', 'Rudi', 'Teguh', 'Irfan', 'Bayu', 'Galih', 'Reza', 'Surya',
        'Gilang', 'Deni', 'Feri', 'Gunawan', 'Hadi', 'Ivan', 'Jefri', 'Kevin', 'Lukman', 'Maman',
        'Nanda', 'Oscar', 'Putra', 'Qori', 'Rangga', 'Satria', 'Taufik', 'Umar', 'Vino', 'Wawan',
        'Yanto', 'Zaki', 'Ade', 'Bima', 'Candra', 'Dedi', 'Erik', 'Fandi', 'Gani', 'Haris',
    ];

    protected static array $firstNamesFemale = [
        'Siti', 'Dewi', 'Nadia', 'Lisa', 'Maya', 'Rina', 'Ani', 'Sri', 'Ratna', 'Fitri',
        'Yuni', 'Dian', 'Wulan', 'Indah', 'Mega', 'Ayu', 'Putri', 'Citra', 'Nurul', 'Kartini',
        'Lina', 'Mira', 'Nina', 'Ovi', 'Pipit', 'Qonita', 'Rini', 'Sari', 'Tina', 'Umi',
        'Vera', 'Wati', 'Yeni', 'Zahra', 'Anita', 'Bella', 'Cici', 'Diana', 'Eka', 'Fina',
        'Gita', 'Hana', 'Ira', 'Julia', 'Kiki', 'Laras', 'Mila', 'Nita', 'Okta', 'Puspita',
    ];

    protected static array $lastNames = [
        'Santoso', 'Wijaya', 'Pratama', 'Setiawan', 'Nugroho', 'Prayoga', 'Kusuma', 'Hidayat',
        'Prasetyo', 'Suryanto', 'Rahman', 'Hakim', 'Fauzi', 'Hartono', 'Prasetya', 'Maulana',
        'Aditya', 'Permana', 'Firmansyah', 'Rahmawati', 'Lestari', 'Anggraini', 'Kartika',
        'Wulandari', 'Suryani', 'Wahyuni', 'Handayani', 'Astuti', 'Permatasari', 'Puspita',
        'Maharani', 'Susanti', 'Saputra', 'Utomo', 'Wibowo', 'Kurniawan', 'Putra', 'Sanjaya',
        'Budiman', 'Halim', 'Gunawan', 'Tanjung', 'Siregar', 'Nasution', 'Harahap', 'Lubis',
        'Simbolon', 'Sitorus', 'Manurung', 'Hutapea', 'Siahaan', 'Panjaitan', 'Simanjuntak',
        'Tampubolon', 'Sinaga', 'Aritonang', 'Napitupulu', 'Pardede', 'Sirait', 'Situmorang',
    ];

    protected function generateUniqueName(): string
    {
        $maxAttempts = 500;
        $attempts = 0;

        do {
            $isMale = $this->faker->boolean();
            $firstName = $isMale
                ? $this->faker->randomElement(self::$firstNamesMale)
                : $this->faker->randomElement(self::$firstNamesFemale);
            $lastName = $this->faker->randomElement(self::$lastNames);
            $fullName = $firstName . ' ' . $lastName;
            $attempts++;

            if ($attempts > $maxAttempts) {
                // Add suffix to make unique
                $fullName = $firstName . ' ' . $lastName . ' ' . self::$nameIndex++;
            }
        } while (in_array($fullName, self::$usedNames) && $attempts <= $maxAttempts);

        self::$usedNames[] = $fullName;
        return $fullName;
    }

    public function definition(): array
    {
        $cities = ['Jakarta', 'Bandung', 'Semarang', 'Surabaya', 'Yogyakarta', 'Denpasar', 'Medan', 'Makassar', 'Palembang', 'Pemalang', 'Tegal'];
        $streets = [
            'Jl. Jenderal Sudirman', 'Jl. MH Thamrin', 'Jl. Asia Afrika', 'Jl. Diponegoro', 'Jl. Malioboro',
            'Jl. Ahmad Yani', 'Jl. Gatot Subroto', 'Jl. Imam Bonjol', 'Jl. Panglima Sudirman', 'Jl. Pemuda',
        ];

        $fullName = $this->generateUniqueName();
        $preferredName = $this->faker->boolean(60) ? explode(' ', $fullName)[0] : null;

        $emailLocalPart = Str::slug($fullName, '.');
        $email = $emailLocalPart . '@basa.test';

        $city = $this->faker->randomElement($cities);
        $street = $this->faker->randomElement($streets);
        $address = $street . ' No. ' . $this->faker->numberBetween(1, 200) . ', ' . $city;

        $phone = '08' . $this->faker->numerify('1#########');
        $whatsapp = '62' . substr($phone, 1);

        $emergencyContactName = $this->faker->randomElement(self::$firstNamesMale) . ' ' . $this->faker->randomElement(self::$lastNames);
        $emergencyWhatsapp = '62' . $this->faker->numerify('8##########');

        return [
            'code' => Str::upper('EMP-'.$this->faker->unique()->numerify('###')),
            'full_name' => $fullName,
            'preferred_name' => $preferredName,
            'email' => $email,
            'phone' => $phone,
            'whatsapp_number' => $whatsapp,
            'date_of_birth' => $this->faker->date(),
            'nik' => $this->faker->unique()->numerify('################'),
            'npwp' => $this->faker->unique()->numerify('##.###.###.#-###.###'),
            'branch_id' => Branch::factory(),
            'department_id' => Department::factory(),
            'position_id' => Position::factory(),
            'manager_id' => null,
            'employment_type' => $this->faker->randomElement(['full_time', 'contract', 'intern']),
            'employment_class' => $this->faker->randomElement(['permanent', 'probation', 'temp']),
            'work_mode' => $this->faker->randomElement(['onsite', 'hybrid', 'remote']),
            'status' => $this->faker->randomElement(['active', 'on_leave', 'probation']),
            'salary_band' => $this->faker->randomElement(['P1', 'P2', 'M1', 'M2', 'M3']),
            'base_salary' => $this->faker->randomElement([4500000, 5000000, 5500000, 6000000, 7000000, 8000000, 10000000, 12000000, 15000000]),
            'payroll_group_id' => null,
            'start_date' => $this->faker->dateTimeBetween('-8 years', 'now')->format('Y-m-d'),
            'probation_end_date' => $this->faker->boolean(60) ? $this->faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d') : null,
            'end_date' => null,
            'emergency_contact_name' => $emergencyContactName,
            'emergency_contact_whatsapp' => $emergencyWhatsapp,
            'bank_name' => $this->faker->randomElement(['BCA', 'Mandiri', 'BNI', 'BRI']),
            'bank_account_number' => $this->faker->bankAccountNumber(),
            'bank_account_name' => $fullName,
            'address' => $address,
            'notes' => $this->faker->boolean(20) ? $this->faker->sentence() : null,
            'meta' => null,
        ];
    }
}
