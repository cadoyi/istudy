<?php foreach ($fields as $field): ?>
        $this->addColumn($this->table, '<?=
            $field['property']
        ?>', $this-><?=
            $field['decorators']
        ?>);
<?php endforeach;

echo $this->render('_addForeignKeys', [
    'table' => $table,
    'foreignKeys' => $foreignKeys,
]);
