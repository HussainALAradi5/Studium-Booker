// comment.js
document.addEventListener("DOMContentLoaded", function () {
  const commentForm = document.getElementById("comment-form")
  const commentInput = document.getElementById("comment-input")

  // Handle form submission for comments
  if (commentForm) {
    commentForm.addEventListener("submit", function (event) {
      event.preventDefault() // Prevent the default form submission
      const comment = commentInput.value.trim()
      const studiumId = document.getElementById("studium_id").value // Hidden input field for studium ID

      if (comment && studiumId) {
        // Send the comment data via AJAX to the server
        submitComment(studiumId, comment)
      } else {
        alert("Please enter a comment before submitting.")
      }
    })
  }

  // Submit the comment via AJAX
  function submitComment(studiumId, comment) {
    const formData = new FormData()
    formData.append("studium_id", studiumId)
    formData.append("comment", comment)

    fetch("index.php?action=submit_comment", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Comment submitted successfully!")
          commentInput.value = "" // Clear the comment input
          loadComments(studiumId) // Reload the comments section after submitting
        } else {
          alert("Failed to submit comment: " + data.message)
        }
      })
      .catch((error) => console.error("Error submitting comment:", error))
  }

  // Function to load comments (to dynamically update the comments section)
  function loadComments(studiumId) {
    fetch("index.php?action=view_studium&id=" + studiumId)
      .then((response) => response.text())
      .then((data) => {
        document.getElementById("comments-section").innerHTML = data
      })
      .catch((error) => console.error("Error loading comments:", error))
  }
})
