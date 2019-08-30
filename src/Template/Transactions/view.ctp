<!-- File: src/Template/Articles/view.ctp -->

<h1><?= h($transaction->id) ?></h1>
<p><?= h($transaction->sourcemac) ?></p>
<p><?= h($transaction->destmac) ?></p>
<p><?= h($transaction->ports) ?></p>
<p><small>Created: <?= $transaction->created?></small></p>

<!-- <p><small>Created: <?= $transaction->created->format(DATE_RFC850) ?></small></p> -->

<p><?= $this->Html->link('Edit', ['action' => 'edit', $transaction->id]) ?></p>
