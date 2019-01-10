<?php foreach ($foreignKeys as $column => $fkData): ?>

        // creates index for column `<?= $column ?>`
        $this->createIndex(
            '<?= $fkData['idx']  ?>',
            $this->table,
            '<?= $column ?>'
        );

        // add foreign key for table `<?= $fkData['relatedTable'] ?>`
        $this->addForeignKey(
            '<?= $fkData['fk'] ?>',
            $this->table,
            '<?= $column ?>',
            '<?= $fkData['relatedTable'] ?>',
            '<?= $fkData['relatedColumn'] ?>',
            'CASCADE'
        );
<?php endforeach;
