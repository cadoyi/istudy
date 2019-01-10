<?php
/**
 * This view is used by console/controllers/MigrateController.php.
 *
 * The following variables are available in this view:
 */
/* @var $className string the new migration class name without namespace */
/* @var $namespace string the new migration class namespace */
/* @var $table string the name table */
/* @var $tableComment string the comment table */
/* @var $fields array the fields */
/* @var $foreignKeys array the foreign keys */

echo "<?php\n";
if (!empty($namespace)) {
    echo "\nnamespace {$namespace};\n";
}
$tableName = '{{%' . $table . '}}';
$table = '$this->table';
?>

use console\db\Migration;

/**
 * Handles the creation of table `<?= $table ?>`.
<?= $this->render('_foreignTables', [
    'foreignKeys' => $foreignKeys,
]) ?>
 */
class <?= $className ?> extends Migration
{
    public $table = '<?= $tableName ?>';

    public $tableOption = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

    /**
     * {@inheritdoc}
     */
    public function up()
    {
<?= $this->render('_createTable', [
    'table' => $table,
    'fields' => $fields,
    'foreignKeys' => $foreignKeys,
])
?>
<?php if (!empty($tableComment)) {
    echo $this->render('_addComments', [
        'table' => $table,
        'tableComment' => $tableComment,
    ]);
}
?>
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
<?= $this->render('_dropTable', [
    'table' => $table,
    'foreignKeys' => $foreignKeys,
])
?>
    }
}
