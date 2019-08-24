<!-- File: src/Template/Sessions/index.ctp  (edit links added) -->

<h1>Sessions</h1>
<p><?= $this->Html->link("Add Session", ['action' => 'add']) ?></p>
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

    <!-- Here's where we iterate through our $sessions query object, printing out session info -->
    <?php foreach ($sessions as $session) : ?>

    <tr>

        <td>
            <?= $this->Html->link($session->id, ['action' => 'view', $session->slug]) ?>
        </td>

        <td>
            <?= $session->sourcemac ?>
        </td>
        <td>
            <?= $session->destmac ?>
        </td>
        <td>
            <?= $session->ports ?>
        </td>
        <td>
            <!-- <?= $session->created ?> -->

            <?= $session->created->format(DATE_RFC850) ?>
        </td>
        <td>
            <?= $this->Html->link('Edit', ['action' => 'edit', $session->slug]) ?>
        </td>
        <td>
            <?= $this->Form->postLink(
                    'Delete',
                    ['action' => 'delete', $session->slug],
                    ['confirm' => 'Are you sure?']
                )
                ?>
        </td>
    </tr>
    <?php endforeach; ?>

</table>