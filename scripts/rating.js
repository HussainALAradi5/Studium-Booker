document.addEventListener("DOMContentLoaded", function () {
  const stars = document.querySelectorAll(".star")
  const ratingValueInput = document.getElementById("rating-value")
  const submitButton = document.querySelector('input[type="submit"]')
  const isRated = parseFloat(ratingValueInput.value) > 0 // Check if the user has already rated

  let selectedRating = parseFloat(ratingValueInput.value) || 0

  // If the user has already rated, lock the stars and disable the submit button
  if (isRated) {
    updateStars(selectedRating, true) // Mark the stars as golden (locked)
    stars.forEach((star) => star.classList.add("locked"))
    submitButton.style.display = "none" // Hide the submit button after rating
  }

  // Hover effect for rating stars (only if the user hasn't rated yet)
  stars.forEach((star) => {
    star.addEventListener("mouseover", function () {
      if (!isRated) {
        // Only allow hovering if the user hasn't rated yet
        const rating = parseFloat(star.dataset.value)
        updateStars(rating)
      }
    })

    star.addEventListener("mouseout", function () {
      if (!isRated) {
        // Only allow resetting if the user hasn't rated yet
        updateStars(selectedRating)
      }
    })

    star.addEventListener("click", function () {
      if (!isRated) {
        // Only allow clicking if the user hasn't rated yet
        selectedRating = parseFloat(star.dataset.value)
        ratingValueInput.value = selectedRating // Update the hidden field with the selected rating
        updateStars(selectedRating, true) // Lock the stars (golden)
        submitButton.disabled = false // Enable the submit button
      }
    })
  })

  // Function to update the star display (highlight or lock stars)
  function updateStars(rating, fixed = false) {
    stars.forEach((star) => {
      const starValue = parseFloat(star.dataset.value)
      if (starValue <= rating) {
        star.classList.add(fixed ? "fixed" : "hover")
      } else {
        star.classList.remove("hover", "fixed")
      }
    })
  }
})
