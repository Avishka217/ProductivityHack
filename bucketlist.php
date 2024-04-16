
<!DOCTYPE html>
<html>

<head>
  <title>Bucketlist</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Bootstrap Datepicker CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
</head>

<body>

  <div class="container mt-5">
    <h2>Add Bucketlist Item</h2>
    <form action='bucketlist.php' method='post' class="mb-3">
      <div class="form-group">
        <label for="item_name">Bucketlist Item:</label>
        <input type='text' name='item_name' class="form-control">
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <h2>Bucketlist</h2>
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Item</th>
            <th>Completion Date</th>
            <th>Completion State</th>
            <th>Details</th>
            <th>Edit</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Include your database connection file
          include 'db_connection.php';

          // Insert new bucketlist item
          if (isset($_POST['item_name'])) {
            $item_name = $_POST['item_name'];
            $sql = "INSERT INTO bucketlist (item_name) VALUES ('$item_name')";
            if ($conn->query($sql) === TRUE) {
              echo "<script>alert('New record created successfully');</script>";
            } else {
              echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
            }
          }

          // Your PHP code for displaying bucketlist items here
          $sql = "SELECT * FROM bucketlist";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              echo "<tr>
                                <td>" . $row["item_name"] . "</td>
                                <td>" . $row["completion_date"] . "</td>
                                <td>" . $row["completion_state"] . "</td>
                                <td>" . $row["details"] . "</td>
                                <td><button onclick='editItem(" . $row["id"] . ")' class='btn btn-primary'>Edit</button></td>
                            </tr>";
            }
          } else {
            echo "<tr><td colspan='5'>0 results</td></tr>";
          }
          $conn->close();
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Bootstrap JS (optional) -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <!-- Bootstrap Datepicker JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

  <!-- JavaScript for editing bucketlist item -->
  <script>
    function editItem(id) {
      var modalHtml = `
            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Bucketlist Item</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editForm">
                                <div class="form-group">
                                    <label for="completion_state">Completion State:</label>
                                    <select class="form-control" id="completion_state" name="completion_state">
                                      <option value="completed">Completed</option>
                                      <option value="not_completed">Not Completed</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="completion_date">Completion Date:</label>
                                    <input type="text" class="form-control datepicker" id="completion_date" name="completion_date">
                                </div>
                                <div class="form-group">
                                    <label for="details">Details:</label>
                                    <textarea class="form-control" id="details" name="details"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" onclick="submitEditForm(${id})">Submit</button>
                        </div>
                    </div>
                </div>
            </div>`;
      $('body').append(modalHtml);
      $('#editModal').modal('show');
      // Initialize Bootstrap Datepicker
      $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
      });
    }

    function submitEditForm(id) {
      var completion_state = $('#completion_state').val();
      var completion_date = $('#completion_date').val();
      var details = $('#details').val();
      var formData = new FormData();
      formData.append('update_id', id);
      formData.append('completion_date', completion_date);
      formData.append('completion_state', completion_state);
      formData.append('details', details);

      fetch('bucketlist.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.text())
        .then(data => {
          alert(data);
          $('#editModal').modal('hide');
          location.reload();
        })
        .catch(error => {
          console.error('Error:', error);
        });
    }
  </script>

</body>

</html>
```