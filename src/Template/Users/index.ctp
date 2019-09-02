<!-- File: src/Template/Users/index.ctp  (edit links added) -->

<?php

?>

<h1>Users</h1>



<p><?= $this->Html->link("Add Users", ['action' => 'add']) ?></p>
<table>
    <tr>
        <th>User Id</th> 
        <th>User name</th>
        <th>User level</th>
        <th>Created</th>
        <!-- <th>Modified</th> -->
       <th></th>
        
    </tr>
    

<!-- Here's where we iterate through our $users query object, printing out user info -->
<?php foreach ($users as $user): ?>
    <tr>
        <td>
        <?= $user->id ?>
        </td>
        <td>
        <?= $user->username ?>

            <!-- <?= $this->Html->link($user->username, ['action' => 'view', $user->id]) ?> -->
        </td>
        <td>
        <?= $user->role ?>
        </td>
        <td>
            <?= $user->created ?>
            <!-- ->format(DATE_RFC850) -->
        </td>
        <!-- <td>
            <?= $user->modified ?>
            ->format(DATE_RFC850)
        </td> -->
        <!-- <td>
            <?= $this->Html->link('Edit', ['action' => 'edit', $user->id]) ?>
        </td> -->
        <td>
            <?= $this->Form->postLink(
                'Delete',
                ['action' => 'delete', $user->id],
                ['confirm' => 'Are you sure?'])
            ?>  
        </td>
    </tr>
<?php endforeach; ?>

</table>

