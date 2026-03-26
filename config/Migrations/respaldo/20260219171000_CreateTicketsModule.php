<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class CreateTicketsModule extends BaseMigration
{
    public function change(): void
    {
        $tickets = $this->table('tickets');
        $tickets
            ->addColumn('folio', 'string', ['limit' => 30, 'null' => true])
            ->addColumn('title', 'string', ['limit' => 180])
            ->addColumn('description', 'text')
            ->addColumn('type', 'string', ['limit' => 30, 'default' => 'error'])
            ->addColumn('priority', 'string', ['limit' => 20, 'default' => 'media'])
            ->addColumn('status', 'string', ['limit' => 30, 'default' => 'nuevo'])
            ->addColumn('requested_by', 'integer')
            ->addColumn('assigned_to', 'integer', ['null' => true])
            ->addColumn('closed_at', 'datetime', ['null' => true])
            ->addTimestamps();

        $tickets->addIndex(['folio'], ['unique' => true]);
        $tickets->addIndex(['status']);
        $tickets->addIndex(['requested_by']);
        $tickets->addIndex(['assigned_to']);
        $tickets->create();

        $updates = $this->table('ticket_updates');
        $updates
            ->addColumn('ticket_id', 'integer')
            ->addColumn('created_by', 'integer')
            ->addColumn('update_type', 'string', ['limit' => 30, 'default' => 'comment'])
            ->addColumn('status_from', 'string', ['limit' => 30, 'null' => true])
            ->addColumn('status_to', 'string', ['limit' => 30, 'null' => true])
            ->addColumn('message', 'text', ['null' => true])
            ->addTimestamps();

        $updates->addIndex(['ticket_id']);
        $updates->addIndex(['created_by']);
        $updates->addIndex(['update_type']);
        $updates->create();

        $attachments = $this->table('ticket_attachments');
        $attachments
            ->addColumn('ticket_id', 'integer')
            ->addColumn('ticket_update_id', 'integer', ['null' => true])
            ->addColumn('created_by', 'integer')
            ->addColumn('original_name', 'string', ['limit' => 255])
            ->addColumn('stored_name', 'string', ['limit' => 255])
            ->addColumn('relative_path', 'string', ['limit' => 255])
            ->addColumn('mime_type', 'string', ['limit' => 120, 'null' => true])
            ->addColumn('file_size', 'integer', ['default' => 0])
            ->addColumn('extension', 'string', ['limit' => 15, 'null' => true])
            ->addTimestamps();

        $attachments->addIndex(['ticket_id']);
        $attachments->addIndex(['ticket_update_id']);
        $attachments->addIndex(['created_by']);
        $attachments->create();

        $notifications = $this->table('ticket_notifications');
        $notifications
            ->addColumn('user_id', 'integer')
            ->addColumn('ticket_id', 'integer')
            ->addColumn('event_type', 'string', ['limit' => 30, 'default' => 'comment'])
            ->addColumn('title', 'string', ['limit' => 180])
            ->addColumn('message', 'string', ['limit' => 255])
            ->addColumn('is_read', 'boolean', ['default' => false])
            ->addColumn('read_at', 'datetime', ['null' => true])
            ->addTimestamps();

        $notifications->addIndex(['user_id']);
        $notifications->addIndex(['ticket_id']);
        $notifications->addIndex(['is_read']);
        $notifications->addIndex(['created']);
        $notifications->create();
    }
}
