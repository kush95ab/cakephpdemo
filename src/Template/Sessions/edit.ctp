<!-- File: src/Template/Sessions/edit.ctp -->

<h1>Edit Session</h1>
<?php
    echo $this->Form->create($session);
    // echo $this->Form->control('user_id', ['type' => 'hidden']);
    // echo $this->Form->control('title');
    echo $this->Form->control('sourcemac', ['rows' => '2']);

    echo $this->Form->control('destmac', ['rows' => '2']);

    echo $this->Form->control('ports', ['rows' => '2']);

    echo $this->Form->button(__('Save Session'));
    echo $this->Form->end();
?>
