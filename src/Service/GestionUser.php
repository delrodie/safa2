<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GestionUser
{
    private UserPasswordHasherInterface $passwordHasher;
    private UserRepository $userRepository;

    public function __construct(UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository)
    {
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
    }

    /**
     * Initialisation du compte super administrateur
     *
     * @return bool
     */
    public function initialisation(): bool
    {
        $verif = $this->userRepository ->findOneBy(['email'=>'delrodieamoikon@gmail.com']);
        if ($verif) return false;

        $user = new User();
        $user->setEmail('delrodieamoikon@gmail.com');
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'FAMILLE'));

        $this->userRepository->save($user, true);

        return true;
    }
}