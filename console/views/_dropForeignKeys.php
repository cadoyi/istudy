<?php foreach ($foreignKeys as $column => $fkData): ?>
        // drops foreign key for table `<?= $fkData['relatedTable'] ?>`
        $this->dropForeignKey(
            '<?= $fkData['fk'] ?>',
            $this->table
        );

        // drops index for column `<?= $column ?>`
        $this->dropIndex(
            '<?= $fkData['idx'] ?>',
            $this->table
        );

<?php endforeach;
