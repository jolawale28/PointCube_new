     <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Queasy.com <script type="text/javascript">document.write((new Date()).getFullYear()); </script></span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-sm btn-primary" type="button" data-dismiss="modal"><i class="fas fa-fw fa-reply"></i> Cancel</button>
          <a class="btn btn-sm btn-warning" href="login.php?loggedout=true"><i class="fas fa-fw fa-sign-out-alt"></i> Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="vendor/chart.js/util.js"></script>
  <script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/datatables-demo.js"></script>

  <script src="js/custom.js"></script>
  <script src="js/award_point.js"></script>
  <script src="js/selectr/dist/selectr.js"></script>
  <!-- <script src="js/demo/Chart11.min.js"></script> -->


  <!---=====Script for House Metric Chart------>
  <!------------------------------------------->
  <script type="text/javascript">
    var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var color = Chart.helpers.color;
    var barChartData = {
      labels: [
          <?php
        $result = read_all("houses_tb", array("blacklist" => 0));

        if ($result)  {
          while($row = mysqli_fetch_array($result)) {
            echo '"' . $row['house_name'] . '",';
          }
        }

      ?>

      ],
      datasets: [{
        label: '+POINTS',
        backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
        borderColor: window.chartColors.blue,
        borderWidth: 1,
        data: [
          <?php
        $result = read_all("houses_tb", array("blacklist" => 0));

        if ($result)  {
          while($row = mysqli_fetch_array($result)) {

            $sql = 'SELECT * FROM cards_tb WHERE validated = 1 AND card_value > 0 AND house_id = ' . $row['house_id'];
            
            $result2 = mysqli_query($connection, $sql);

            if ($result2) {
              echo "'" . mysqli_num_rows($result2) . "',";
            }
          }
        }

      ?>
        ]
      }, {
        label: '-POINTS',
        backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
        borderColor: window.chartColors.red,
        borderWidth: 1,
        data: [
          <?php
        $result = read_all("houses_tb", array("blacklist" => 0));

        if ($result)  {
          while($row = mysqli_fetch_array($result)) {

            $sql = 'SELECT * FROM cards_tb WHERE validated = 1 AND card_value < 0 AND house_id = ' . $row['house_id'];
            
            $result2 = mysqli_query($connection, $sql);

            if ($result2) {
              echo "'" . mysqli_num_rows($result2) . "',";
            }
          }
        }

      ?>
        ]
      }]

    };

    window.onload = function() {
      var ctx = document.getElementById('house_metric').getContext('2d');
      window.myBar = new Chart(ctx, {
        type: 'bar',
        data: barChartData,
        options: {
          responsive: true,
          legend: {
            position: 'top',
          },
          title: {
            display: true,
            text: 'Points Per House'
          }
        }
      });

    };

    document.getElementById('randomizeData').addEventListener('click', function() {
      var zero = Math.random() < 0.2 ? true : false;
      barChartData.datasets.forEach(function(dataset) {
        dataset.data = dataset.data.map(function() {
          return zero ? 0.0 : randomScalingFactor();
        });

      });
      window.myBar.update();
    });

    var colorNames = Object.keys(window.chartColors);
    document.getElementById('addDataset').addEventListener('click', function() {
      var colorName = colorNames[barChartData.datasets.length % colorNames.length];
      var dsColor = window.chartColors[colorName];
      var newDataset = {
        label: 'Dataset ' + (barChartData.datasets.length + 1),
        backgroundColor: color(dsColor).alpha(0.5).rgbString(),
        borderColor: dsColor,
        borderWidth: 1,
        data: []
      };

      for (var index = 0; index < barChartData.labels.length; ++index) {
        newDataset.data.push(randomScalingFactor());
      }

      barChartData.datasets.push(newDataset);
      window.myBar.update();
    });

    document.getElementById('addData').addEventListener('click', function() {
      if (barChartData.datasets.length > 0) {
        var month = MONTHS[barChartData.labels.length % MONTHS.length];
        barChartData.labels.push(month);

        for (var index = 0; index < barChartData.datasets.length; ++index) {
          // window.myBar.addData(randomScalingFactor(), index);
          barChartData.datasets[index].data.push(randomScalingFactor());
        }

        window.myBar.update();
      }
    });

    document.getElementById('removeDataset').addEventListener('click', function() {
      barChartData.datasets.pop();
      window.myBar.update();
    });

    document.getElementById('removeData').addEventListener('click', function() {
      barChartData.labels.splice(-1, 1); // remove the label first

      barChartData.datasets.forEach(function(dataset) {
        dataset.data.pop();
      });

      window.myBar.update();
    });
  </script>
<!-- <script src="https://ajax.cloudflare.com/cdn-cgi/scripts/a2bd7673/cloudflare-static/rocket-loader.min.js" data-cf-settings="0337e97a7e2a00a163f0430c-|49" defer=""></script> -->

  <?php

    include("inc/charts.php");

  ?>

</body>

</html>
