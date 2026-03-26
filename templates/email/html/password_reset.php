<?php
/**
 * @var string $resetLink
 * @var \App\Model\Entity\User $user
 */
?>
<p>Hola <?= h($user->name) ?>,</p>
<p>Recibimos una solicitud para restablecer tu contraseña. Si no fuiste tú, puedes ignorar este correo.</p>
<p>Para continuar, haz clic en el siguiente enlace:</p>
<p><a href="<?= $resetLink ?>"><?= $resetLink ?></a></p>
<p>Este enlace expirará en 1 hora.</p>