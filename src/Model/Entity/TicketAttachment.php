<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class TicketAttachment extends Entity
{
    protected array $_accessible = [
        'ticket_id' => true,
        'ticket_update_id' => true,
        'created_by' => true,
        'original_name' => true,
        'stored_name' => true,
        'relative_path' => true,
        'mime_type' => true,
        'file_size' => true,
        'extension' => true,
        'created' => true,
        'modified' => true,
        'ticket' => true,
        'ticket_update' => true,
        'creator' => true,
    ];
}
