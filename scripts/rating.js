// rating.js - Handles rating star interactions
document.addEventListener("DOMContentLoaded", function () {
  const stars = document.querySelectorAll(".star")
  let selectedRating = 0

  // Hover effect
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
      document.getElementById("rating-value").value = selectedRating
      updateStars(selectedRating, true) // Mark the stars as fixed (golden)
    })
  })

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
