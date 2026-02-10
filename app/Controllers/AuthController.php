<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;
use Core\Auth;
use Core\Controller;
use Core\Csrf;
use Core\Logger;
use Core\Request;
use Core\Response;
use Core\Validator;

final class AuthController extends Controller
{
    public function showLogin(Request $request): void
    {
        $this->view('auth/login', ['csrf' => Csrf::token()]);
    }

    public function login(Request $request): void
    {
        $email = (string) $request->input('email', '');
        $password = (string) $request->input('password', '');

        if (!Validator::email($email) || !Validator::required($password)) {
            $this->view('auth/login', ['csrf' => Csrf::token(), 'error' => 'Invalid credentials format.']);
            return;
        }

        $user = (new AuthService())->attemptLogin($email, $password);
        if (!$user) {
            Logger::info('Failed login', ['email' => $email, 'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown']);
            $this->view('auth/login', ['csrf' => Csrf::token(), 'error' => 'Invalid credentials or inactive account.']);
            return;
        }

        Auth::login((int) $user['id']);
        Response::redirect('/dashboard');
    }

    public function logout(Request $request): void
    {
        Auth::logout();
        Response::redirect('/login');
    }
}
