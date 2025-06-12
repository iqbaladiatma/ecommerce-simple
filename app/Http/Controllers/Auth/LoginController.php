<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
  use AuthenticatesUsers;

  protected $redirectTo = RouteServiceProvider::HOME;

  public function __construct()
  {
    $this->middleware('guest')->except('logout');
  }

  protected function authenticated(Request $request, $user)
  {
    Log::info('User logged in', [
      'user_id' => $user->id,
      'email' => $user->email,
      'has_admin_role' => $user->hasRole('admin')
    ]);

    if ($user->hasRole('admin')) {
      return redirect()->route('filament.admin.pages.dashboard');
    }

    return redirect()->route('user.dashboard');
  }
}
