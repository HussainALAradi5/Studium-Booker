<?php


// Validate user authentication
$is_logged_in = false;
$user_id = null;

try {
  $user_id = validate_user_logged_in(); // Use the user validation function
  $is_logged_in = true;
} catch (Exception $e) {
  // User not logged in
}

// Fetch available studiums based on current and future date/time range
$current_date = date('Y-m-d H:i:s');
$future_date = date('Y-m-d H:i:s', strtotime('+2 hours')); // Example: next 2 hours

$available_studiums = get_available_studiums($current_date, $future_date);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Studium Reservation</title>
  <link rel="stylesheet" href="./css/reservation.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
  <div class="reservation-container">
    <h1>Reserve a Studium</h1>

    <?php if ($is_logged_in): ?>
      <div class="availability-section">
        <h2>Available Studiums</h2>

        <!-- Reservation Form -->
        <form id="reservation-form">
          <label for="daterange">Select Date Range:</label>
          <input type="text" id="daterange" class="calendar-input" required>

          <div id="reservation-info" class="reservation-info hidden">
            <p id="cost-per-hour"></p>
            <p id="total-price"></p>
          </div>

          <button type="submit" class="reserve-btn hidden">Reserve</button>
        </form>

        <ul id="available-studiums">
          <?php foreach ($available_studiums as $studium): ?>
            <li data-studium-id="<?php echo $studium['studium_id']; ?>" class="studium-item studium-available">
              <span><?php echo htmlspecialchars($studium['studium_name']); ?> - <?php echo htmlspecialchars($studium['price_per_hour']); ?> BD/hour</span>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

    <?php endif; ?>
  </div>

  <script>
    $(document).ready(function() {
      // Initialize Flatpickr for Date Range Selection
      flatpickr("#daterange", {
        mode: "range",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr, instance) {
          // Apply filter when date range is selected
          filterStudiums(dateStr.split(' to '));
        }
      });

      // Function to calculate total price
      function calculateTotalPrice(pricePerHour, totalHours) {
        return pricePerHour * totalHours;
      }

      // Function to filter available studiums based on selected date/time
      function filterStudiums(dateRange) {
        const [startAt, endAt] = dateRange;

        if (startAt && endAt) {
          $('#available-studiums .studium-item').each(function() {
            const studiumId = $(this).data('studium-id');
            $(this).toggleClass('hidden', false); // Show all studiums as available initially
          });

          // Update reservation info
          const studiumId = $('#available-studiums .studium-item').first().data('studium-id');
          const pricePerHour = parseFloat($('#available-studiums li[data-studium-id="' + studiumId + '"] span').text().split('-')[1].trim().split(' ')[0]);
          const totalHours = (new Date(endAt) - new Date(startAt)) / (1000 * 60 * 60);
          const totalPrice = calculateTotalPrice(pricePerHour, totalHours);

          $('#cost-per-hour').text(`Cost per Hour: ${pricePerHour} BD`);
          $('#total-price').text(`Total Price: ${totalPrice.toFixed(2)} BD`);
          $('#reservation-info').removeClass('hidden');
          $('.reserve-btn').removeClass('hidden');
        }
      }

      $('#reservation-form').on('submit', function(e) {
        e.preventDefault(); // Prevent form submission

        const studiumId = $('#available-studiums .studium-item').first().data('studium-id');
        const daterange = $('#daterange').val().split(' to ');

        $.ajax({
          url: './views/studium/reservation.php',
          type: 'POST',
          data: {
            action: 'reserve',
            studium_id: studiumId,
            start_at: daterange[0],
            end_at: daterange[1]
          },
          success: function(response) {
            try {
              const data = JSON.parse(response);
              alert(data.message);

              if (data.success) {
                // Remove reserved studium from the list
                $(`li[data-studium-id="${studiumId}"]`).remove();
                $('#reservation-info').addClass('hidden');
                $('.reserve-btn').addClass('hidden');
              }
            } catch (error) {
              console.error('JSON Parsing Error:', error);
              alert('An error occurred while processing the reservation.');
            }
          },
          error: function() {
            alert('An error occurred while processing your request.');
          }
        });
      });
    });
  </script>
</body>

</html>