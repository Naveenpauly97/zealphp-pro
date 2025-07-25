<?php

namespace ZealPHP\Services;

use ZealPHP\Models\User;
use ZealPHP\Repositories\UserRepository;
use ZealPHP\G;
use ZealPHP\Session;

use function ZealPHP\elog;

class AuthService
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function login(string $username, string $password): ?User
    {
        $user = $this->userRepository->findByUsername($username);

        if (!$user) {
            $user = $this->userRepository->findByEmail($username);
        }

        if ($user && $user->verifyPassword($password)) {
            $this->setUserSession($user);
            Session::set('user', $user->toArray());
            elog("User logged in: " . $user->username);
            return $user;
        }

        elog("Failed login attempt for: $username", "warning");
        return null;
    }

    public function register(array $userData): ?User
    {
        // Validate required fields
        if (empty($userData['username']) || empty($userData['email']) || empty($userData['password'])) {
            return null;
        }

        // Check if user already exists
        if ($this->userRepository->usernameExists($userData['username'])) {
            elog("Registration failed: Username already exists - " . $userData['username'], "warning");
            return null;
        }

        if ($this->userRepository->emailExists($userData['email'])) {
            elog("Registration failed: Email already exists - " . $userData['email'], "warning");
            return null;
        }

        // Validate password strength
        if (strlen($userData['password']) < 6) {
            elog("Registration failed: Password too short", "warning");
            return null;
        }

        $user = $this->userRepository->create($userData);

        if ($user) {
            $this->setUserSession($user);
            elog("User registered: " . $user->username);
        }

        return $user;
    }

    public function logout(): void
    {
        $g = G::instance();
        unset($g->session['user_id']);
        unset($g->session['user']);
        elog("User logged out");
    }

    public function getCurrentUser(): ?User
    {
        $g = G::instance();

        if (empty($g->session['user_id'])) {
            return null;
        }

        // Check if user is cached in session
        if (!empty($g->session['user'])) {
            return new User($g->session['user']);
        }

        // Load user from database
        $user = $this->userRepository->findById($g->session['user_id']);

        if ($user) {
            $g->session['user'] = $user->toArray();
        }

        return $user;
    }

    public function isAuthenticated(): bool
    {
        return $this->getCurrentUser() !== null;
    }

    public function requireAuth(): User
    {
        $user = $this->getCurrentUser();

        if (!$user) {
            http_response_code(401);
            header('Location: /login');
            exit('Unauthorized');
        }

        return $user;
    }

    private function setUserSession(User $user): void
    {
        $g = G::instance();
        $g->session['user_id'] = $user->id;
        $g->session['user'] = $user->toArray();
    }

    public function validateUserOwnership(int $userId): bool
    {
        $currentUser = $this->getCurrentUser();
        return $currentUser && $currentUser->id === $userId;
    }
}