<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel='stylesheet' type='text/css' href='css/navbar.css'>
    <link rel='stylesheet' type='text/css' href='css/profile.css'>
    <link rel='stylesheet' type='text/css' href='css/employee-contents.css'>
    <title>Document</title>
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: index.php");
    }

    include 'extras/employee-navbar.php';
    include 'extras/profile.php'
    ?>
    <!--DUMMY DIV-->
    <div id='file'></div>

    <div class='background-container'></div>

    <div class='add-document-overlay'></div>
    <div class='employee-contents' id='con'>
        <!--WORK DOCUMENTS-->
        <div class='e-contents-header-app'>
            <div class='document-header-btn'>
                <h1>DOCUMENTS</h1>
            </div>
            <div class='all-docs-header'>
                <h2>All (Latest - Oldest)</h2>
                <div class='sortation-docs'>
                    <select id='sortation-docs' value='all-desc'>
                        <option value='all-desc'>All (Latest - Oldest)</option>
                        <option value='all-asc'>All (Oldest - Latest)</option>
                        <option value='prescriptions'>Prescriptions</option>
                        <option value='labresults'>Lab Results</option>
                        <option value='today'>Today</option>
                        <option value='thisweek'>This Week</option>
                        <option value='thismonth'>This Month</option>
                    </select>
                    <button id='sort-table-docs'>Sort</button>
                </div>
            </div>
        </div>

        <div class='e-contents-table'>
            <div class='e-contents-header-table-docs four-fr'>
                <span>Document Type</span>
                <span>Patient</span>
                <span>Date Uploaded</span>
                <span></span>
            </div>

            <!-- TABLE CONTENTS -->
            <div class="dynamic-tbl">

                <?php
                include 'php_processes/db_conn.php';

                $offset = 0;

                if (isset($_POST['offset'])) {
                    $offset = $_POST['offset'];
                }


                $query = "SELECT * FROM documents ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT $offset, 5";

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

                ?>

            </div>
        </div>

        <div class="reload-all">
            <div>
                <!-- <button id='hard-prev'>&#60;&#60;</button> -->
                <button id='prev'>&#60;</button>
                <span id='page-num'>1</span>
                <span id='offset'>0</span>
                <button id='next'>&gt;</button>
                <!-- <button id='hard-next'>&gt;&gt;</button> -->
            </div>
            <button type='button' class='reload-tbl-doc-2' value='pres'>Reload Table</button>
        </div>
    </div>
</body>
<script src='js/navbar.js'></script>
<script src='js/doctor-documents.js'></script>

</html>