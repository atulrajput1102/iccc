<?php include "pages/config.php";
header("Access-Control-Allow-Origin: *");

?>
<html lang="en">
  <head>
  
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ICCC Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="assets/vendors/jvectormap/jquery-jvectormap.css">
    <link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icons.min.css">
    <link rel="stylesheet" href="assets/vendors/owl-carousel-2/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/vendors/owl-carousel-2/owl.theme.default.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="assets/images/favicon.png" />
    <style>
    .selected-row {
        background-color: #d1e7dd; /* Light green background for the selected row */
        position: relative;
        z-index: 1000; /* Ensure it appears on top */
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2); /* Optional: Add a shadow to highlight */
    }
    .alert-icon {
        font-size: 20px;
        color: #FF0000; /* Red color for the icon */
        cursor: pointer;
        position: relative;
        top: 20px;
        right: 50px;
        left: 750px;
        margin-right: 40px;
        z-index: 1000;
    }
    .alert-notification {
        display: none;
        position: fixed;
        top: 15%;
        left: 70%;
        transform: translate(-50%, -50%);
        background-color: #000000;
        border: 1px solid #FFFFFF;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        width: 300px; /* Adjust the width as needed */
        text-align: center; /* Center text inside the notification */
    }
    .alert-notification p {
        margin: 0;
    }
</style>

  </head>
  <body>
    <div class="container-scroller">
      
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
          <a class="sidebar-brand brand-logo" href="index.html"><img src="assets/images/lc.jpg" alt="logo" /></a>
          <a class="sidebar-brand brand-logo-mini" href="index.html"><img src="assets/images/lc.jpg" alt="logo" /></a>
        </div>
        <?php include "pages/sidebar.php"?>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar p-0 fixed-top d-flex flex-row">
          <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
            <a class="navbar-brand brand-logo-mini" href="index.html"><img src="../../../assets/images/logo-mini.svg" alt="logo" /></a>
          </div>
          <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
              <span class="mdi mdi-menu"></span>
            </button>

            <!-- <div id="alertIcon" class="alert-icon">&#x1F6A8;  Alert</div> --><!-- Alert icon (exclamation mark) -->
             <!-- <div id="alertNotification" class="alert-notification">
                  <p id="alertMessage">No new alerts</p>

                  <div id="alertNotification" >
    <p id="alertMessage" style="margin: 0 0 0 0;right:70px; font-size: 8px; color: #721c24;"></p>
    <button id="okButton" style="background-color: #007bff; color: white; border: none;  border-radius: 1px; cursor: pointer;">OK</button>
</div>

              </div>  -->
            
            <?php include "pages/header.php";?>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
              <span class="mdi mdi-format-line-spacing"></span>
            </button>
          </div>
        </nav>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
		 
           
			<div class="row ">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">VMS Message</h4><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exLargeModal" onclick=openCreateForm() id="openModal">Create</button>
                    <div class="table-responsive">
                      <table id="vmsdashboard" class="table table-striped" style="width:100%">
                        <thead>
                          <tr>
                            
                            <th> Message </th>
							<th> Location </th>
                            <th> Date </th>
                            <th> Active</th>
                            <th> Action </th>
                          </tr>
                        </thead>
                        
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Modal start -->
           <div id="updateModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">VMS Message Update</h5>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Content will be loaded here via AJAX -->
                    <div id="modalContent"></div>
                </div>
                <div class="modal-footer">
                     
                </div>
            </div>
        </div>
    </div>
           <!-- Modal start --> 
        
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
         
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="assets/vendors/chart.js/chart.umd.js"></script>
    <script src="assets/vendors/progressbar.js/progressbar.min.js"></script>
    <script src="assets/vendors/jvectormap/jquery-jvectormap.min.js"></script>
    <script src="assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="assets/vendors/owl-carousel-2/owl.carousel.min.js"></script>
    <script src="assets/js/jquery.cookie.js" type="text/javascript"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/misc.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="assets/js/proBanner.js"></script>
    <script src="assets/js/dashboard.js"></script>
	<script src="assets/js/chart.js"></script> 
	<script src="assets/js/jquery-3.7.1.js"></script> 
	 
	<script src="assets/js/dataTables.js"></script> 
	<script src="assets/js/dataTables.bootstrap5.js"></script> 
	<script src="assets/js/dataTables.buttons.js"></script> 
	<script src="assets/js/buttons.bootstrap5.js"></script> 
	<script src="assets/js/jszip.min.js"></script> 
	<script src="assets/js/pdfmake.min.js"></script> 
	<script src="assets/js/vfs_fonts.js"></script> 
	<script src="assets/js/buttons.html5.min.js"></script> 
	<script src="assets/js/buttons.print.min.js"></script> 
	<script src="assets/js/buttons.colVis.min.js"></script> 
	
    <script>
    var API_URL = '<?php echo $API_URL; ?>';
    var dTable;

    function openCreateForm() {
        $.ajax({
            url: 'create_vms_mess.php', // URL of the PHP page with the form
            type: 'GET',
            success: function(response) {
                $('#modalContent').html(response);
                $('#updateModal').modal('show');
            },
            error: function(err) {
                $('#modalContent').html('<p>Failed to load content.</p>' + err.message);
                $('#updateModal').modal('show');
            }
        });
    }

    function openUpdateForm(id) {
        $.ajax({
            url: 'update_vms_mess.php?id=' + id, // URL of the PHP page with the form
            type: 'GET',
            success: function(response) {
                $('#modalContent').html(response);
                $('#updateModal').modal('show');
            },
            error: function(err) {
                $('#modalContent').html('<p>Failed to load content.</p>' + err.message);
                $('#updateModal').modal('show');
            }
        });
    }

    function refreshDataTable() {
        dTable.destroy();
        getVMSData();
    }

    function getVMSData() {
    $.ajax({
        url: API_URL + '/GetQuery/SELECT * FROM vms_alert ORDER BY isActive DESC, created_date DESC',
        type: 'POST',
        crossDomain: true,
        data: {},
        success: function(response) {
            const jsonObject = JSON.parse(response);
            dTable = $('#vmsdashboard').DataTable({
                layout: {
                    topStart: {
                        buttons: ['copy', 'excel', 'pdf', 'colvis']
                    }
                },
                data: jsonObject,
                columns: [
                    { data: 'message' },
                    { data: 'location' },
                    { data: 'created_date' },
                    {
                        data: 'isActive',
                        render: function(data, type, row) {
                            return '<input type="checkbox" class="form-check-input" ' + (data == 1 ? 'checked' : '') + ' disabled>';
                        }
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            let buttons = '<button type="button" class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#exLargeModal" onclick="openUpdateForm(\'' + data + '\')">Update</button>';
                            if (row.isActive == 1) {
                                buttons += '<button type="button" class="btn btn-success" onclick="sendToBoard(\'' + row.message + '\', \'' + row.location + '\', ' + row.isActive + ')">Send</button>';
                            }
                            return buttons;
                        }
                    }
                ],
                order: [[3, 'asc']] // Sort by 'isActive' column (index 3) in ascending order, then 'created_date' (index 2)
            });
        },
        error: function(err) {
            alert('Error: ' + err.message);
        }
    });
}


function sendToBoard(message, location, isActive) {
    $.ajax({
        url: 'vms_mess_board.php', // PHP file containing the socket code
        type: 'POST',
        data: {
            message: message,
            location: location,
            isActive: isActive
        },
        success: function(response) {
            if (response.trim() === "Message received and processed") {
                alert("Message sent and processed successfully.");
                // Ensure other code runs if needed
                // Example: Call another function or perform another action
            } else {
                alert("Unexpected response: " + response);
            }
        },
        error: function(err) {
            alert('Error: ' + err.message);
        }
    });
}

// // Example usage after sending the message
// const message = "Accident Ahead Go Slow";
// const locations = "CH 1+700 - LHS"; // Chainage number as the location
// const isActive = true;

// Send data to the VMS
// sendToBoard(message, locations, isActive);




    $(document).ready(function() {
        getVMSData();
      /*
        function playBeep() {
            var audioContext = new (window.AudioContext || window.webkitAudioContext)();
            var oscillator = audioContext.createOscillator();
            oscillator.type = 'triangle'; // Waveform type: 'sine', 'square', 'sawtooth', 'triangle'
            oscillator.frequency.setValueAtTime(1000, audioContext.currentTime); // Frequency in Hz
            oscillator.connect(audioContext.destination);
            oscillator.start();
            setTimeout(function() {
                oscillator.stop();
            }, 100); // Duration of beep in milliseconds
        }
      */
        // function checkForNewAlert() {
        //     $.ajax({
        //         url: API_URL + '/GetQuery/SELECT message FROM vms_alert ORDER BY id DESC LIMIT 1;',
        //         type: 'POST',
        //         success: function(response) {
        //             try {
        //                 var parsedResponse = JSON.parse(response);
        //                 if (parsedResponse.length > 0) {
        //                     var alertMessage = parsedResponse[0].message; // Access the 'alert' value

        //                     if (alertMessage) {
        //                         if (sessionStorage.getItem('acknowledgedAlert') !== alertMessage) {
        //                             $('#alertMessage').text(alertMessage);
        //                             $('#alertNotification').fadeIn(300);
        //                             playBeep();
        //                             sessionStorage.setItem('currentAlert', alertMessage);
        //                         }
        //                     }
        //                 } else {
        //                     console.log('No new alert');
        //                 }
        //             } catch (error) {
        //                 console.error('Error parsing alert response:', error);
        //             }
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('Error fetching alert:', error);
        //         }
        //     });
        // }

        // $('#okButton').on('click', function() {
        //     var currentAlert = sessionStorage.getItem('currentAlert');
        //     if (currentAlert) {
        //         sessionStorage.setItem('acknowledgedAlert', currentAlert);
        //     }
        //     $('#alertNotification').fadeOut(300);
        // });

        // setInterval(checkForNewAlert, 3000);

        // $('#alertIcon').on('click', function() {
        //     $('#alertNotification').toggle();
        // });

        // $('#vmsdashboard tbody').on('click', 'tr', function() {
        //     $('#vmsdashboard tbody tr').removeClass('selected-row');
        //     $(this).addClass('selected-row');
        //     $(this)[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
        // });
    });
</script>

    <!-- End custom js for this page -->
  </body>
</html>