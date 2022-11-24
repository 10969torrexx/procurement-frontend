<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- <script src="js/admin/sweetalert.min.js"></script> -->

<?php 
  if(isset($session['status']) && $session['status'] !=''){
    ?>
      <script>
          swal({
              title: "<?php echo $session['status'];?>",
              text: "You clicked the button!",
              icon: "status",
              button: "Aww yiss!",
            });
      </script>
    <?php
  }
?>
<!-- <script>
          swal({
              title: "xdvsdf",
              text: "You clicked the button!",
              icon: "success",
              button: "Aww yiss!",
            });
      </script> -->
    <!--  -->