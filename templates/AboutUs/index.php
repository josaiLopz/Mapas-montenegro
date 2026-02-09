<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\AboutU> $aboutUs
 */
?>
<div class="aboutUs index content">
    <?= $this->Html->link(__('New About U'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('About Us') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('title') ?></th>
                    <th><?= $this->Paginator->sort('image') ?></th>
                    <th><?= $this->Paginator->sort('active') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('updated') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($aboutUs as $aboutU): ?>
                <tr>
                    <td><?= $this->Number->format($aboutU->id) ?></td>
                    <td><?= h($aboutU->title) ?></td>
                    <td><?= h($aboutU->image) ?></td>
                    <td><?= h($aboutU->active) ?></td>
                    <td><?= h($aboutU->created) ?></td>
                    <td><?= h($aboutU->updated) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $aboutU->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $aboutU->id]) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $aboutU->id],
                            [
                                'method' => 'delete',
                                'confirm' => __('Are you sure you want to delete # {0}?', $aboutU->id),
                            ]
                        ) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>