<!-- File: src/Template/Transactions/add.ctp -->

<h1>Add Transaction</h1>
<?php
    echo $this->Form->create($transactions);
    // Hard code the user for now.
    // echo $this->Form->control('user_id', ['type' => 'hidden', 'value' => 1]);
    // echo $this->Form->control('title');
    echo $this->Form->control('sourcemac', ['rows' => '2']);

    echo $this->Form->control('destmac', ['rows' => '2']);

    echo $this->Form->control('ports', ['rows' => '2']);

    echo $this->Form->button(__('Save Transaction'));
    echo $this->Form->end();


?>
