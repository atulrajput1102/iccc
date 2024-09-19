<?php include "pages/config.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <style>
        *, *:after, *:before {
            box-sizing: border-box;
        }

        #multi_option {
            max-width: 100%;
            width: 350px;
        }

        label {
            display: block;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .vscomp-toggle-button {
            padding: 10px 30px 10px 10px;
            border-radius: 5px;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="assets/css/virtual-select.min.css">
</head>
<body>
<div class="row">
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <label for="vms_message" class="col-sm-3 col-form-label">Message</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="vms_message" placeholder="VMS Message">
                </div>
            </div>
            
            <div class="form-group row">
                <label for="location" class="col-sm-3 col-form-label">Location</label>
                <div class="col-sm-9">
                    <select id="multipleselect" multiple name="native-select" placeholder="Select Location" data-search="true" data-silent-initial-value-set="true">
                        <option value="Jaipur">Jaipur</option>
                        <option value="Rewari">Rewari</option>
                        <option value="Delhi">Delhi</option>
                        <option value="Bhopal">Bhopal</option>
                        <option value="Indore">Indore</option>
                        <option value="Noida">Noida</option>
                        <option value="Kota">Kota</option>
                        <option value="Jabalpur">Jabalpur</option>
                    </select>
                </div>
            </div>

            <script type="text/javascript" src="assets/js/virtual-select.min.js"></script>
            <script type="text/javascript">
                VirtualSelect.init({ 
                    ele: '#multipleselect' 
                });
            </script>

            <div class="form-group row">
                <label for="isActive" class="col-sm-3 col-form-label">Status</label>
                <div class="col-sm-9">
                    <input type="checkbox" class="form-check-input" id="isActive">
                </div>
            </div>
            <button class="btn btn-primary me-2" id="updateANPR" onclick="CreateVMSMessage()">Create</button>
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var API_URL = '<?php echo $API_URL; ?>';

        // Function to create VMS message
        window.CreateVMSMessage = function() {
            var selectedOptions = $('#multipleselect').val();
            var vms_message = $('#vms_message').val();
            var isActive = $("#isActive").is(':checked') ? 1 : 0;

            // First update all old entries to inactive
           var query_status = "UPDATE vms_alert SET isActive = 0 WHERE isActive = 1 ";
            $.ajax({
                url: API_URL + '/UPDATE/'+query_status, // Update endpoint to deactivate old entries
                type: 'POST',
                data: { locations: selectedOptions },
                success: function() {
                    // After updating old entries, insert the new message
                    selectedOptions.forEach(function(location) {
                        var data = {
                            location: location,
                            message: vms_message,
                            isActive: isActive
                        };
                        var query = "insert into vms_alert (location, message, created_date, isActive) values ('" 
                    + location + "', '" 
                    + vms_message + "', NOW(), '" 
                    + isActive + "')";

                // Send each query via AJAX   
                $.ajax({
                    url: API_URL + '/UPDATE/' + encodeURIComponent(query), // Assuming your backend handles direct SQL
                    type: 'POST',
                            data: data,
                            success: function(response) {
                                console.log('Successfully inserted for location: ' + location);
                            },
                            error: function(err) {
                                console.log('Error inserting for location: ' + location + ' | Error: ' + err.responseText);
                            }
                        });
                    });

                    // Redirect after all AJAX calls complete
                    setTimeout(function() {
                        window.location.href = 'vms_mess.php';
                    }, 500);
                },
                error: function(err) {
                    console.log('Error updating status | Error: ' + err.responseText);
                }
            });
        };
    });
</script>
</body>
</html>
