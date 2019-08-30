<!-- File: src/Template/Articles/index.ctp  (edit links added) -->

<?php

?>

<h1>Articles</h1>



<p><?= $this->Html->link("Add Article", ['action' => 'add']) ?></p>
<table>
    <tr>
        <th>Title</th>
        <th>Created</th>
        <th></th>
        <th></th>
        
    </tr>
    

<!-- Here's where we iterate through our $articles query object, printing out article info -->
<?php foreach ($articles as $article): ?>
    <tr>
        <td>
            <?= $this->Html->link($article->title, ['action' => 'view', $article->id]) ?>
        </td>
        <td>
            <?= $article->created->format(DATE_RFC850) ?>
        </td>
        <td>
            <?= $this->Html->link('Edit', ['action' => 'edit', $article->id]) ?>
        </td>
        <td>
            <?= $this->Form->postLink(
                'Delete',
                ['action' => 'delete', $article->id],
                ['confirm' => 'Are you sure?'])
            ?>  
        </td>
    </tr>
<?php endforeach; ?>

</table>

