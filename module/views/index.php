<?php
/* @var $model \module\model\Vacation[] */

?>


<div class="text-right">
    <a href="/index.php?r=create">Create vacation requests</a>
</div>
<div class="text-left">
    <strong>Total days per year:</strong> <?= $total; ?> <br/>
    <strong>Used:</strong> <?= $used; ?> days<br/>
    <strong>Days left:</strong> <?= $total - $used; ?><br/>
</div>
<table style="width:100%">
    <tr>
        <th>Requested days</th>
        <th>Request data</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($model as $item): ?>
        <tr>
            <td><?= $item->days ?></td>
            <td><?= $item->getFormattedCreated() ?></td>
            <td>
                <?= $item->getIsApproved() ? 'Approved' : 'Not approved' ?>
            </td>
            <td>
                <a href="/index.php?r=update&id=<?= $item->id ?>">Update</a>
                <a href="/index.php?r=delete&id=<?= $item->id ?>">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>