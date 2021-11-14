<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel='stylesheet' type='text/css' href='css/navbar.css'>
    <link rel='stylesheet' type='text/css' href='css/profile.css'>
    <link rel='stylesheet' tyype='text/css' href='css/employee-contents.css'>
    <title>Document</title>
</head>

<body>
    <?php
    include 'extras/employee-navbar.php';
    include 'extras/profile.php'
    ?>

    <!--THIS INVISIBLE IFRAME IS FOR PREVENTING FORM REDIRECTIONS-->
    <iframe name="dummyframe" id="dummyframe" style="display: none;"></iframe>
    <div class='background-container'></div>

    <div class="notification-area">
        <div class="notification-box">
            <div class='notif-header'>
                <span>Notifications</span>
            </div>
            <div class="notif-contents">
                <!--10 People Have booked for appointments-->
                <?php

                include 'php_processes/db_conn.php';

                $query = "SELECT * FROM notifications WHERE date_booked > DATE_SUB(NOW(), INTERVAL '1' DAY)";

                $result = mysqli_query($conn, $query);
                $number = mysqli_num_rows($result) - 1;
                $arrayNames = array();

                while ($row = mysqli_fetch_array($result)) {
                    $fullname = $row['patient_fullname'];

                    if (!in_array($fullname, $arrayNames)) {
                        array_push($arrayNames, $fullname);
                    }
                }

                if (sizeof($arrayNames) == 1) {
                    echo "
                        <div class='notif-content book-notif-type'>
                            <div class='notif-img'></div>
                            <span>
                                $arrayNames[0] has booked for appointment
                            </span>
                            <div class='seen'>
                                <div class='seen-circle'></div>
                            </div> 
                        </div>
                    ";
                } else if (sizeof($arrayNames) == 0) {
                    echo "<span class = 'no-new'>No New Notifications!</span>";
                } else {
                    $size = sizeof($arrayNames) - 1;
                    echo "
                        <div class='notif-content book-notif-type'>
                            <div class='notif-img'></div>
                            <span>
                                $arrayNames[0] and $size other/s has booked for appointment
                            </span>
                            <div class='seen'>
                                <div class='seen-circle'></div>
                            </div> 
                        </div>
                    ";
                }

                ?>


                <!-- A patient has settled a bill -->
                <!-- <div class="notif-content bill-notif-type">
                    <div class='notif-img'></div>
                    <span>
                        Marc Dwain B. Magracia has settled a bill
                    </span>
                    <div class="seen">
                        <div class="seen-circle"></div>
                    </div>
                </div> -->

                <!--A patient has requested an appointment reschedule. Accept?-->
                <!-- <div class="notif-content resched-notifgit type">
                    <div class='notif-img'></div>
                    <span>
                        Marc Dwain Magracia requested an appointment reschedule. Accept?
                    </span>
                    <div class="seen">
                        <div class="seen-circle"></div>
                    </div>
                </div> -->

                <!--A patient has settled a bill-->
                <!-- <div class="notif-content bill-notif-type">
                    <div class='notif-img'></div>
                    <span>
                        Marc Dwain B. Magracia has settled a bill
                    </span>
                    <div class="seen">
                        <div class="seen-circle"></div>
                    </div>
                </div> -->

                <!--A patient has settled a bill-->
                <!-- <div class="notif-content bill-notif-type">
                    <div class='notif-img'></div>
                    <span>
                        Marc Dwain B. Magracia has settled a bill
                    </span>
                    <div class="seen">
                        <div class="seen-circle"></div>
                    </div>
                </div> -->

                <div class='notif-see-all'>
                    <span>See All</span>
                </div>
            </div>
        </div>
        <div class="notification-num"><span>0</span></div>
        <div class="notification-btn">
            <i class="far fa-bell"></i>
        </div>
    </div>

    <div class='employee-contents'>
        <!--APPOINTMENT UPCOMING-->
        <div class='e-contents-header-app'>
            <h1>Appointments</h1>
            <h2>Today</h2>
        </div>

        <div class='e-contents-table'>
            <div class='e-contents-header-table'>
                <span>Appointment No.</span>
                <span>Patient</span>
                <span>Date/Time</span>
                <span>Contact No.</span>
            </div>

            <div id='doctor-appt-table'>
                <!-- TABLE CONTENTS -->
                <?php
                include 'php_processes/db_conn.php';

                $query = "SELECT * FROM appointments WHERE date(date_and_time) = CURDATE()";

                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        $appointmentnum = $row['appointment_num'];
                        $fullname = $row['patient_fullname'];
                        $datetime = $row['date_and_time'];
                        $dt = new DateTime($datetime);

                        $date = $dt->format('F j, Y l');
                        $time = $dt->format('h:i A');

                        echo "
                                <div class='e-contents'>
                                    <span>$appointmentnum</span>
                                    <span>$fullname</span>
                                    <span>$datetime</span>
                                    <span class = 'e-num'>
                                        0998390813
                                        <button><i class='fas fa-ellipsis-v'></i></button>
                                    </span>
                                    <form class = 'dropdown' target = 'dummyframe'>
                                        <button type = 'button' class = 'cancel-appointment' value = '$appointmentnum'>Cancel Appointment</button>
                                    </form>
                                </div>
                            ";
                    }
                } else {
                    echo '
                            <span class = "no-appointments">You currently have no appointments</span>
                        ';
                }
                ?>
            </div>
        </div>

        <div class="reload-all">
            <button type='button' id='reload-tbl'>Reload Table</button>
            <button id='see-all-appt'>See All Appointments</button>
            <button id='see-all-appt'>Add an Appointment</button>
        </div>



        <!--APPOINTMENT HISTORY-->
        <div class='e-contents-header'>
            <h2>History</h2>
        </div>

        <div class='e-contents-table'>
            <div class='e-contents-header-table'>
                <span>Appointment No.</span>
                <span>Patient</span>
                <span>Date/Time</span>
                <span>Status</span>
            </div>

            <!-- TABLE CONTENTS -->
            <div class='e-contents'>
                <span>1902983721367</span>
                <span>Marc Dwain Magracia</span>
                <span>19/03/2021 5:00PM</span>
                <span>Appointed</span>
            </div>

            <div class='e-contents'>
                <span>1902983721367</span>
                <span>Marc Dwain Magracia</span>
                <span>19/03/2021 5:00PM</span>
                <span>Appointed</span>
            </div>

            <div class='e-contents'>
                <span>1902983721367</span>
                <span>Marc Dwain Magracia</span>
                <span>19/03/2021 5:00PM</span>
                <span>Appointed</span>
            </div>
        </div>
    </div>
</body>
<script src='js/navbar.js'></script>
<script src='js/notification.js'></script>
<script src='js/appointment-manager.js'></script>

</html>