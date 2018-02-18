<?php

use module\model\Vacation;


/* @var $model \module\model\VacationForm */
/* @var $action string */
/* @var $total integer */
/* @var $used integer */
?>
<div class="vacation">
    <strong>Total days per year:</strong> <?= $total; ?> <br/>
    <strong>Used:</strong> <?= $used; ?> days<br/>
    <strong>Days left:</strong> <?= $total - $used; ?><br/>

    <form action="index.php?r=<?= $action; ?>" method="post">
        <div>
            <label for="days">How much days?</label>
            <input name="days" id="days" type="number" value="<?= $model->days ?>" required>
            <div class="error">
                <?= $model->getError('days'); ?>
            </div>
        </div>
        <div>
            <label>
                <select name="status">
                    <option value="1" <?= (int)$model->status === Vacation::STATUS_APPROVED ? 'selected' : '' ?>>Approved</option>
                    <option value="0" <?= (int)$model->status === Vacation::STATUS_NOT_APPROVED ? 'selected' : '' ?>>Not approved</option>
                </select>
            </label>
            <div class="error">
                <?= $model->getError('status'); ?>
            </div>
        </div>

        <button type="submit">Submit</button>
        <a href="/index.php">Cancel</a>
    </form>
</div>