<?php
namespace App\Repositories;
use App\Models\User;

class UserRepositories

{
    public function findOrcreateByGoogle(array $googleUser): User
    {
        return User::updateOrCreate(
            ['google_id'=>$googleUser['google_id']],
            [
                'name'=>$googleUser['name'],
                'email'=>$googleUser['email'],
                'avatar'=>$googleUser['avatar'],
                'email_verified_at'=>now(),
            ]
        );
    }

    public function updateFcmToken(User $user, string $token): void
    {
        $user->update(['fcm_token'=>$token]);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

}
