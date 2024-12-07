document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("comment-form")
  const responseMessage = document.getElementById("comment-response")

  if (form) {
    form.addEventListener("submit", async function (event) {
      event.preventDefault() // Prevent the default form submission
      
      const formData = new FormData(form)

      try {
        const response = await fetch("./index.php?action=studium", {
          method: "POST",
          body: formData,
        })

        const result = await response.json()

        if (result.success) {
          responseMessage.textContent = "Comment added successfully!"
          responseMessage.style.color = "green"

          // Optionally, dynamically refresh the comments section
          const commentsSection = document.querySelector(".comment-section")
          if (commentsSection) {
            const newComment = document.createElement("div")
            newComment.classList.add("comment")
            newComment.innerHTML = `
              <p><strong>You</strong> says:</p>
              <p>${formData.get("comment")}</p>
              <p><small>Just now</small></p>
            `
            commentsSection.prepend(newComment)
          }

          form.reset() // Clear the form
        } else {
          responseMessage.textContent = result.message
          responseMessage.style.color = "red"
        }
      } catch (error) {
        console.error("Error submitting comment:", error)
        responseMessage.textContent = "An error occurred. Please try again."
        responseMessage.style.color = "red"
      }
    })
  }
})
