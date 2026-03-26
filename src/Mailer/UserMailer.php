<?php
declare(strict_types=1);

namespace App\Mailer;

use App\Model\Entity\User;
use Cake\Mailer\Mailer;

class UserMailer extends Mailer
{
    public function passwordReset(User $user, string $resetLink): void
    {
        $this
            ->setFrom(['noreply@montenegroeditores.net' => 'Mapa Distribuidores Montenegro']) // ✅ 1 solo
            ->setReplyTo('noreply@montenegroeditores.net') // opcional
            ->setTo($user->email)
            ->setSubject('Restablecer tu contraseña')
            ->setEmailFormat('both')
            ->setViewVars([
                'user' => $user,
                'resetLink' => $resetLink,
            ]);

        $this->viewBuilder()
            ->setTemplate('password_reset')
            ->setLayout('default');
    }
}