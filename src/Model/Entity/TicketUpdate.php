<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class TicketUpdate extends Entity
{
    protected array $_accessible = [
        'ticket_id' => true,
        'created_by' => true,
        'update_type' => true,
        'status_from' => true,
        'status_to' => true,
        'message' => true,
        'created' => true,
        'modified' => true,
        'ticket' => true,
        'creator' => true,
        'ticket_attachments' => true,
    ];
}
