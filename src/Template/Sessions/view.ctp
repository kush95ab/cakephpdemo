<!-- File: src/Template/Articles/view.ctp -->

<h1><?= h($session->id) ?></h1>
<p><?= h($session->sourcemac) ?></p>
<p><?= h($session->destmac) ?></p>
<p><?= h($session->ports) ?></p>
<p><small>Created: <?= $session->created?></small></p>

<!-- <p><small>Created: <?= $session->created->format(DATE_RFC850) ?></small></p> -->

<p><?= $this->Html->link('Edit', ['action' => 'edit', $session->slug]) ?></p>
