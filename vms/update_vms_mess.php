<?php include "pages/config.php";?>
<html>
<head>
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
	<script src="assets/js/bootstrap.bundle.min.js"></script> 
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
</head>



<body>

<div class="row">

              
                <div class="card">
                  <div class="card-body">
                    
                    
                      <div class="form-group row">
                        <label for="exampleInputUsername2" class="col-sm-3 col-form-label">Message</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" id="vms_message" placeholder="VMS Message" >
                        </div>
                      </div>
                     
                      <div class="form-group row">
                        <!-- <label for="exampleInputMobile" class="col-sm-3 col-form-label">Location</label> -->
                        <div class="col-sm-9">
                          <input type="hidden" class="form-control" id="location" placeholder="Location">
						  
						  <input type="hidden" class="form-control" id="location_id" placeholder="Location" value="<?=$_GET['id']?>">
                        </div>
                      </div>
					   <div class="form-group row">
                        <label for="exampleInputMobile" class="col-sm-3 col-form-label">Active</label>
                        <div class="col-sm-9">
                          <input type="checkbox" class="form-check-input" id="isActive" >
                        </div>
                      </div>
					  
                      
                      <button  class="btn btn-primary me-2" id="updateVMS" onclick="updateVMS()" data-bs-dismiss="modal" data-dismiss="modal">Update</button>
                      <button class="btn btn-secondary"  data-bs-dismiss="modal" data-dismiss="modal" >Cancel</button>
                   
                  </div>
                </div>
             
			  </body>
			  <script>
			  

var API_URL='<?php echo $API_URL;?>'	 
function updateVMS(){
	var location = $('#location').val();
	var vms_message = $('#vms_message').val();
	var id = $('#location_id').val();
		
            if ($("#isActive").is(':checked') == true) {
                isActive=1;
            } else {
                isActive=0;
            }
        
	
	var query = "update vms_alert set location='"+location+"',message='"+vms_message+"',isActive='"+isActive+"'  where id='"+id+"'";

	 $.ajax({
    url: API_URL+'/UPDATE/'+query,
    type: 'POST',
    
	 crossDomain:true,
	
   data: {}, 
    success: function(response){
		refreshDataTable();
		
	    
    },
    error: function(err){
        alert('error'+err.message);
    }
});
}
$(document).ready(function() {
	
	var id = $('#location_id').val();	
	var query="SELECT * FROM `vms_alert` where id='"+id+"'"
	
		 $.ajax({
    url: API_URL+"/GetQuery/"+query,
    type: 'POST',
    
	 crossDomain:true,
	
   data: {}, 
    success: function(response){
		
		const jsonObject = JSON.parse(response)
		 $.each(jsonObject, function(key, row) {
			
			$('#location').attr('value', row.location);
			$('#vms_message').attr('value', row.message);
			$('#location_id').attr('value', row.id);
			if(row.isActive == 1){
			$('#isActive').attr('checked', "true");
			
			}
			
			
		 });
	    
    },
    error: function(err){
        alert('error'+err.message);
    }
});
});
		
</script>
</html>