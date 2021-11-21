<?php include 'includes/bootstrap.php';

/* @var $db */
/* @var $settings */

// Delete if selected
if(isset($_GET['delete'])) {
    $table = $_GET['delete'];
    $db->query("DROP TABLE IF EXISTS `$table`")->execute();
}

// Get all Tables
$tables = $db->query('SHOW TABLES')->fetchAll();

// HTML ----------------------------------------------------------------------------------------------------------------
include 'includes/head.php'; ?>

<p>
    <a href="start.php">Import Wizard..</a>
</p>

<table>
    <tr>
        <th>
            Database:
        </th>
        <td colspan="2">
            <?=$settings['dbname']?>
        </td>
    </tr>
    <?php $rowspan = true; ?>
    <?php foreach ($tables as $table) { ?>
    <?php foreach ($table as $table_name) { ?>
    <tr>
        <?php if ($rowspan) { ?>
        <th rowspan="<?=count($tables)?>">Tables:</th>
        <?php $rowspan = false; ?>
        <?php } ?>
        <td><?=$table_name?></td>
        <td><a href="?delete=<?=$table_name?>">[Delete]</a></td>
    </tr>
    <?php } ?>
    <?php } ?>
</table>

<?php include 'includes/foot.php';