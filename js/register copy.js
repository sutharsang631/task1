//Select the form and submit button
const signupForm = document.querySelector("#signup-form");
const signupBtn = document.querySelector("#signup-btn");

//Listen for the submit event on the form
signupForm.addEventListener("submit", function (e) {
  e.preventDefault();

  //Get the values of the username, password, and email inputs
  const username = document.querySelector("#username").value;
  const password = document.querySelector("#password").value;
  const email = document.querySelector("#email").value;

  //Save the values to local storage
  localStorage.setItem("username", username);
  localStorage.setItem("password", password);
  localStorage.setItem("email", email);

  //Redirect to the profile page
  window.location.href = "profile.html";
});
