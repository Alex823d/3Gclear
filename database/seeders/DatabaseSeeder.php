<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (config('app.env') !== 'production') {
            $this->call(\App\Modules\Admin\Database\Seeds\AdminSeeder::class);
        }
        $this->call(\App\Modules\Admin\Database\Seeds\PermissionSeeder::class);
        $this->call(\App\Modules\Admin\Database\Seeds\RoleSeeder::class);

        if (config('admin.fake_data_seeder')) {
            //$this->fakeDataSeeders();
        }
    }

    private function fakeDataSeeders()
    {
        $this->call(CitizenshipFakeDataSeeder::class);
        $this->call(CompanyActivitiesFakeDataSeeder::class);
        $this->call(DoctorTypeSeeder::class);
        $this->call(ActivitiesFakeDataSeeder::class);
        $this->call(LanguageFakeDataSeeder::class);
        $this->call(CustomerFakeDataSeeder::class);
        $this->call(EventFakeDataSeeder::class);
        $this->call(CompanyFakeDataSeeder::class);
    }

}
