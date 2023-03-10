$(document).ready(function () {
  $.ajax({
    type: "GET",
    url: "https://your-backend-url.com/profile",
    success: function (response) {
      document.getElementById("email").value = response.email;
      document.getElementById("age").value = response.age;
      document.getElementById("dob").value = response.dob;
      document.getElementById("contact").value = response.contact;
    },
  });
});
document
  .getElementById("profile-form")
  .addEventListener("submit", function (event) {
    event.preventDefault();
    var email = document.getElementById("email").value;
    var age = document.getElementById("age").value;
    var dob = document.getElementById("dob").value;
    var contact = document.getElementById("contact").value;
    $.ajax({
      type: "PUT",
      url: "https://your-backend-url.com/profile",
      data: { email: email, age: age, dob: dob, contact: contact },
      success: function (response) {
        if (response.status === "success") {
          alert("Profile updated successfully!");
        } else {
          alert("Failed to update profile. Please try again.");
        }
      },
    });
  });
