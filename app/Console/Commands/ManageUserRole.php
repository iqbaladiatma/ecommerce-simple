<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class ManageUserRole extends Command
{
  protected $signature = 'user:role {action : Action to perform (add/remove)} {email : User email} {role : Role to assign/remove}';
  protected $description = 'Manage user roles (admin only)';

  public function handle()
  {
    // Check if the current user is admin
    if (!Auth::check() || !Auth::user()->hasRole('admin')) {
      $this->error('Only administrators can manage user roles!');
      return 1;
    }

    $action = $this->argument('action');
    $email = $this->argument('email');
    $role = $this->argument('role');

    $user = User::where('email', $email)->first();

    if (!$user) {
      $this->error("User with email {$email} not found!");
      return 1;
    }

    if ($action === 'add') {
      if ($user->hasRole($role)) {
        $this->warn("User {$email} already has the {$role} role!");
        return 0;
      }

      $user->assignRole($role);
      $this->info("Role {$role} has been assigned to user {$email}!");
    } elseif ($action === 'remove') {
      if (!$user->hasRole($role)) {
        $this->warn("User {$email} doesn't have the {$role} role!");
        return 0;
      }

      $user->removeRole($role);
      $this->info("Role {$role} has been removed from user {$email}!");
    } else {
      $this->error('Invalid action! Use "add" or "remove".');
      return 1;
    }

    return 0;
  }
}
