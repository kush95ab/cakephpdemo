<!-- File: src/Template/Transactions/index.ctp  (edit links added) -->

<h1>Transactions</h1>
<p><?= $this->Html->link("Add Transaction", ['action' => 'add']) ?></p>
<table>
    <tr>
        <th>id</th>
        </th>
        <th>Source Mac</th>
        <th>Destination Mac</th>
        <th>No of Interfaces</th>
        <th>Created</th>
        <th></th>
        <th></th>



    </tr>

    <!-- Here's where we iterate through our $transactions query object, printing out transaction info -->
    <?php foreach ($transactions as $transaction) : ?>

    <tr>

        <td>
            <?= $this->Html->link($transaction->id, ['action' => 'view', $transaction->id]) ?>
        </td>

        <td>
            <?= $transaction->sourcemac ?>
        </td>
        <td>
            <?= $transaction->destmac ?>
        </td>
        <td>
            <?= $transaction->ports ?>
        </td>
        <td>
            <!-- <?= $transaction->created ?> -->

            <?= $transaction->created->format(DATE_RFC850) ?>
        </td>
        <td>
            <?= $this->Html->link('Edit', ['action' => 'edit', $transaction->id]) ?>
        </td>
        <td>
            <?= $this->Form->postLink(
                    'Delete',
                    ['action' => 'delete', $transaction->id],
                    ['confirm' => 'Are you sure?']
                )
                ?>
        </td>
    </tr>
    <?php endforeach; ?>

</table>