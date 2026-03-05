<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use App\Models\NotificationSetting;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        // Criar usuários
        $user1 = User::create([
            'name' => 'User One',
            'email' => 'user1@email.com',
            'password' => Hash::make('password'),
        ]);

        $user2 = User::create([
            'name' => 'User Two',
            'email' => 'user2@email.com',
            'password' => Hash::make('password'),
        ]);

        // Configuração de notificação
        NotificationSetting::create([
            'user_id' => $user1->id,
            'days_before' => 3
        ]);

        NotificationSetting::create([
            'user_id' => $user2->id,
            'days_before' => 2
        ]);

        // Criar tarefas
        for ($i = 1; $i <= 20; $i++) {

            $user = $i <= 10 ? $user1 : $user2;

            Task::create([
                'user_id' => $user->id,
                'title' => "Task {$i}",
                'description' => "Descrição da tarefa {$i}",
                'due_date' => Carbon::now()->addDays(rand(1, 7)),
                'is_completed' => false
            ]);
        }
    }
}