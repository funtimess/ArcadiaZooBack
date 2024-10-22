<?php

namespace App\Tests\Entity;

use App\Entity\Utilisateur;
use PHPUnit\Framework\TestCase;

class UtilisateurTest extends TestCase
{
    public function testAnException(): void
    {
        $this->expectException(\TypeError::class);

        $user = new Utilisateur();
        $user->setNom([10]);
    }
}