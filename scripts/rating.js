// rating.js
document.addEventListener("DOMContentLoaded", function () {
  const ratingStars = document.querySelectorAll(".star-rating span")
  let currentRating = 0 // Store the current rating while hovering

  // Handle hovering over the stars (interactive rating)
  ratingStars.forEach((star) => {
    star.addEventListener("mouseover", function () {
      let rating = parseFloat(star.getAttribute("data-value"))
      currentRating = rating
      updateStarRatingDisplay(currentRating)
    })

    star.addEventListener("mouseout", function () {
      updateStarRatingDisplay(currentRating)
    })

    // Handle the final click on a star (submit the rating)
    star.addEventListener("click", function () {
      const studiumId = document.getElementById("studium_id").value // Hidden input field for studium ID
      const ratingValue = currentRating

      if (studiumId && ratingValue) {
        // Send the rating data via AJAX to the server
        submitRating(studiumId, ratingValue)
      }
    })
  })

  // Update the star rating display based on hover or click
  function updateStarRatingDisplay(rating) {
    ratingStars.forEach((star) => {
      const starValue = parseFloat(star.getAttribute("data-value"))
      if (starValue <= rating) {
        star.classList.add("filled")
      } else {
        star.classList.remove("filled")
      }
    })
  }

  // Submit the rating via AJAX
  function submitRating(studiumId, rating) {
    const formData = new FormData()
    formData.append("studium_id", studiumId)
    formData.append("rating", rating)

    fetch("index.php?action=submit_rating", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Rating submitted successfully!")
          updateStarRatingDisplay(rating) // Update the UI to reflect the final rating
        } else {
          alert("Failed to submit rating: " + data.message)
        }
      })
      .catch((error) => console.error("Error submitting rating:", error))
  }
})
