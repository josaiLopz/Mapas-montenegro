<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class TicketNotification extends Entity
{
    protected array $_accessible = [
        'user_id' => true,
        'ticket_id' => true,
        'event_type' => true,
        'title' => true,
        'message' => true,
        'is_read' => true,
        'read_at' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'ticket' => true,
    ];
}
