<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

#[Signature('staeze:reset-admin {--email=admin@admin.com} {--password=password}')]
#[Description('Setzt den Admin-User zurueck: Passwort neu, super_admin-Rolle zuweisen.')]
class ResetAdminUser extends Command
{
    public function handle(): int
    {
        $email = (string) $this->option('email');
        $password = (string) $this->option('password');

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->warn("User mit E-Mail {$email} nicht gefunden – lege neu an.");
            $user = User::create([
                'name' => 'Admin',
                'email' => $email,
                'password' => bcrypt($password),
            ]);
        } else {
            $user->password = bcrypt($password);
            $user->save();
            $this->info("Passwort von {$email} zurueckgesetzt.");
        }

        $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $user->syncRoles([$role]);

        $this->info('Rolle super_admin zugewiesen.');
        $this->newLine();
        $this->table(['Feld', 'Wert'], [
            ['E-Mail', $user->email],
            ['Passwort', $password],
            ['Rolle', 'super_admin'],
        ]);

        return self::SUCCESS;
    }
}
