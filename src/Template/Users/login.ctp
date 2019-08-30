<!-- File: src/Template/Users/login.ctp -->

<div class="users form" >
<?= $this->Flash->render() ?>
<?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Please enter your username and password') ?></legend>
        <?= $this->Form->control('username') ?>
        <?= $this->Form->control('password') ?>
    </fieldset>
<?= $this->Form->button(__('Login'));?>
    
<p><?= $this->Html->link("Sign up here ", ['action' => 'add']) ?></p>
<?= $this->Form->end() ?>
</div>
