<?php

include 'db_conn.php';

$sort_value = $_POST['sortval'];

$offset = 0;

if (isset($_POST['offset'])) {
    $offset = $_POST['offset'];
}

if ($sort_value == 'all-desc') {
    $query = "SELECT * FROM documents ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT $offset, 5";
} else if ($sort_value == 'all-asc') {
    $query = "SELECT * FROM documents ORDER BY UNIX_TIMESTAMP(date_uploaded) ASC LIMIT $offset, 5";
} else if ($sort_value == 'prescriptions') {
    $query = "SELECT * FROM documents WHERE doc_type = 'prescription' ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT $offset, 5";
} else if ($sort_value == 'labresults') {
    $query = "SELECT * FROM documents WHERE doc_type = 'labresult' ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT $offset, 5";
} else if ($sort_value == 'today') {
    $query = "SELECT * FROM documents WHERE DATE(date_uploaded) = CURDATE() ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT $offset, 5";
} else if ($sort_value == 'thisweek') {
    $query = "SELECT * FROM documents WHERE YEARWEEK(date_uploaded, 1) = YEARWEEK(CURDATE(), 1) ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT $offset, 5";
} else if ($sort_value == 'thismonth') {
    $query = "SELECT * FROM documents WHERE MONTH(date_uploaded) = MONTH(CURRENT_DATE()) ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT $offset, 5";
}

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<div class = 'empty'>No Documents Found</div>";
} else {
    while ($row = mysqli_fetch_array($result)) {
        $doc_type = ucwords($row['doc_type']);
        $pname = $row['patient_name'];
        $date_uploaded = strtotime($row['date_uploaded']);
        $date_up_formatted = date('M d, Y h:i A', $date_uploaded);
        $doc_num = $row['doc_num'];

        echo "
            <div class='e-contents four-fr'>
                <span>$doc_type</span>
                <span>$pname</span>
                <span>$date_up_formatted</span>
                <div>
                    <button class = 'view' value = '$doc_num'>View</button>
                    <button class = 'download-pdf' value = '$doc_num'><i class='fas fa-download'></i></button>
                </div>
            </div>
        ";
    }
}
