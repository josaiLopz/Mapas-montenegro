<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Ticket extends Entity
{
    protected array $_accessible = [
        'folio' => true,
        'title' => true,
        'description' => true,
        'type' => true,
        'priority' => true,
        'status' => true,
        'requested_by' => true,
        'assigned_to' => true,
        'closed_at' => true,
        'created' => true,
        'modified' => true,
        'requester' => true,
        'assignee' => true,
        'ticket_updates' => true,
        'ticket_attachments' => true,
    ];
}
