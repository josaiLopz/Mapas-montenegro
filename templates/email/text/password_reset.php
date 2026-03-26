<?php
/**
 * @var string $resetLink
 * @var \App\Model\Entity\User $user
 */
?>
Hola <?= h($user->name) ?>,

Recibimos una solicitud para restablecer tu contraseña. Si no fuiste tú, puedes ignorar este correo.

Para continuar, copia y pega el siguiente enlace en tu navegador:
<?= $resetLink ?>

Este enlace expirará en 1 hora.