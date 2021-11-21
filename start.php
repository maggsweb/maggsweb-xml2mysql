<?php include 'includes/bootstrap.php';

/* @var $settings */
/* @var $db */

$_SESSION = [];

// Process Generate SQL form
if (isset($_POST['frmName']) && $_POST['frmName'] == 'generateSql') {

    $xmlFile = $_FILES['frmXmlFile'];

    if ($xmlFile['error']) {
        dump('File upload error, Code: '.$xmlFile['error']);
        exit;
    }
    if ($xmlFile['type'] != 'text/xml') {
        dump('File must be XML');
        exit;
    }

    $uploadedFile = $settings['upload_dir'] . '/' . time() . '.xml';

    if (!move_uploaded_file($xmlFile['tmp_name'], $uploadedFile)) {
        dump('Error moving uploaded file');
        exit;
    }

    $xmlObject = simplexml_load_file($uploadedFile);
    if (!($xmlObject instanceof SimpleXMLElement)) {
        dump('Error converting to XML');
        exit;
    }

    $_SESSION['file'] = $uploadedFile;

    // Create table_name
    $table_name = false;
    foreach($xmlObject as $k => $v){
        $table_name = (string)$k;
        break;
    }

    // create columns
    $tmp = [];
    foreach($xmlObject as $Object){
        foreach($Object as $k => $v) {
            $tmp[$k] = true;
        }
    }
    $table_columns = array_keys($tmp);

    // Generate SQL
    $sql = "CREATE TABLE `$table_name` (";
    foreach($table_columns as $column) {
        $sql .= "\n `$column` varchar(255),";
    }
    // Remove last comma
    $sql = substr($sql,0, -1);

    // Add optional lines
    //$sql .= "\n -- PRIMARY KEY(`  `)";
    //$sql .= "\n -- UNIQUE KEY `  ` (`  `)";
    $sql .= "\n);";

    $_SESSION['sql'] = $sql;

    header("location: create.php");
    exit;
}

// HTML ----------------------------------------------------------------------------------------------------------------
include 'includes/head.php'; ?>

<h2>1. Select XML File</h2>

<form action="" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend>1</legend>
        <input type="file" name="frmXmlFile">
        <button>Generate</button>
    </fieldset>
    <input type="hidden" name="frmName" value="generateSql">
</form>

<h2 class="grey">2. Create Table</h2>

<h2 class="grey">3. Import Records</h2>

<h2 class="grey">4. Result</h2>

<?php include 'includes/foot.php';