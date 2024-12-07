document.addEventListener("DOMContentLoaded", function () {
  const stars = document.querySelectorAll(".star")
  const ratingValueInput = document.createElement("input")
  ratingValueInput.type = "hidden"
  ratingValueInput.name = "rating"
  document.body.appendChild(ratingValueInput) // Append hidden input to the body for submitting rating

  let selectedRating = 0 // Initially, no rating

  // Hover effect for rating stars
  stars.forEach((star) => {
    star.addEventListener("mouseover", function () {
      const rating = parseFloat(star.dataset.value)
      updateStars(rating)
    })

    star.addEventListener("mouseout", function () {
      updateStars(selectedRating)
    })

    star.addEventListener("click", function () {
      selectedRating = parseFloat(star.dataset.value)
      ratingValueInput.value = selectedRating // Update the hidden input with the selected rating
      updateStars(selectedRating, true)
      submitRating(selectedRating) // Automatically submit the rating to the server
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

  // Function to submit the rating using AJAX
  function submitRating(rating) {
    const formData = new FormData()
    formData.append("studium_id", studiumId)
    formData.append("user_id", userId)
    formData.append("rating", rating)

    fetch("index.php?action=studium&id=" + studiumId, {
      // changed to `studium.php`
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        console.log("Rating submitted:", data)
      })
      .catch((error) => {
        console.error("Error submitting rating:", error)
      })
  }
})
