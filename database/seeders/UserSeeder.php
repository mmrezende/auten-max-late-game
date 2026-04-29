<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\PaymentPlan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $testUserCpf = env('TEST_USER_CPF') ?: '11111111111';
        $testAdminCpf = env('TEST_ADMIN_CPF') ?: '22222222222';
        $testUserPhone = env('TEST_USER_PHONE') ?: '11990000001';
        $testAdminPhone = env('TEST_ADMIN_PHONE') ?: '11990000002';
        $testUserPassword = env('TEST_USER_PASSWORD') ?: 'password';
        $testAdminPassword = env('TEST_ADMIN_PASSWORD') ?: 'password';
        $testSubscriberPassword = env('TEST_SUBSCRIBER_PASSWORD') ?: 'password';
        $monthlyPlan = PaymentPlan::query()->where('period', 'monthly')->firstOrFail();

        UserFactory::new()
            ->count(20)
            ->create();
        
        User::create([
            'name' => 'Cliente Teste',
            'cpf' => $testUserCpf,
            'phone' => $testUserPhone,
            'email' => 'cliente@teste.com',
            'password' => Hash::make($testUserPassword),
            'is_admin' => false,
            'email_verified_at' => now(),
            'created_at' => now()->subDays(80),
        ]);

        User::create([
            'name' => 'Admin Teste',
            'cpf' => $testAdminCpf,
            'phone' => $testAdminPhone,
            'email' => 'admin@teste.com',
            'password' => Hash::make($testAdminPassword),
            'is_admin' => true,
            'email_verified_at' => now()
        ]);

        $subscriber = new User([
            'name' => 'Assinante Mensal Teste',
            'cpf' => env('TEST_SUBSCRIBER_CPF') ?: '33333333333',
            'phone' => env('TEST_SUBSCRIBER_PHONE') ?: '11990000003',
            'email' => env('TEST_SUBSCRIBER_EMAIL') ?: 'assinante@teste.com',
            'password' => Hash::make($testSubscriberPassword),
            'is_admin' => false,
            'payment_method' => 'pix',
            'email_verified_at' => now(),
            'created_at' => now()->subDays(80),
            'last_login' => now()->subDay(),
        ]);

        $subscriber->payment_plan()->associate($monthlyPlan);
        $subscriber->save();

        Payment::create([
            'datetime' => now()->subDays(5),
            'date_of_expiration' => now()->addDays(25),
            'price' => $monthlyPlan->price,
            'payment_method' => 'pix',
            'status' => 'approved',
            'payment_plan_id' => $monthlyPlan->id,
            'user_id' => $subscriber->id,
        ]);
    }
}
