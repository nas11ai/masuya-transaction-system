<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        $provinces = [
            'DKI Jakarta',
            'Jawa Barat',
            'Jawa Tengah',
            'Jawa Timur',
            'Bali',
            'Sumatera Utara',
            'Sulawesi Selatan',
            'Kalimantan Timur'
        ];

        $cities = [
            'DKI Jakarta' => ['Jakarta Pusat', 'Jakarta Selatan', 'Jakarta Utara', 'Jakarta Barat', 'Jakarta Timur'],
            'Jawa Barat' => ['Bandung', 'Bekasi', 'Depok', 'Bogor', 'Cimahi'],
            'Jawa Tengah' => ['Semarang', 'Solo', 'Yogyakarta', 'Magelang'],
            'Jawa Timur' => ['Surabaya', 'Malang', 'Sidoarjo', 'Gresik'],
            'Bali' => ['Denpasar', 'Badung', 'Gianyar', 'Tabanan'],
            'Sumatera Utara' => ['Medan', 'Binjai', 'Pematangsiantar'],
            'Sulawesi Selatan' => ['Makassar', 'Parepare', 'Palopo'],
            'Kalimantan Timur' => ['Balikpapan', 'Samarinda', 'Bontang'],
        ];

        $province = fake()->randomElement($provinces);
        $city = fake()->randomElement($cities[$province]);

        $companyTypes = ['PT', 'CV', 'UD', 'Toko'];
        $companyNames = [
            'Maju Jaya',
            'Sejahtera Abadi',
            'Sukses Mandiri',
            'Prima Electronics',
            'Mega Store',
            'Sentosa Jaya',
            'Cahaya Terang',
            'Bintang Lima'
        ];

        return [
            'code' => strtoupper(fake()->unique()->bothify('CUST###??')),
            'name' => fake()->randomElement($companyTypes) . ' ' . fake()->randomElement($companyNames),
            'address' => fake()->streetAddress(),
            'province' => $province,
            'city' => $city,
            'district' => fake()->city(),
            'sub_district' => fake()->streetName(),
            'postal_code' => fake()->postcode(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->companyEmail(),
            'is_active' => fake()->boolean(95), // 95% active
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}
