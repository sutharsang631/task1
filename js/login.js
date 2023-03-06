//Select the form and submit button
const loginForm = document.querySelector("#login-form");
const loginBtn = document.querySelector("#login-btn");

//Listen for the submit event on the form
loginForm.addEventListener("submit", function (e) {
  e.preventDefault();

  //Get the values of the username and password inputs
  const username = document.querySelector("#username").value;
  const password = document.querySelector("#password").value;

  //Get the values stored in local storage
  const storedUsername = localStorage.getItem("username");
  const storedPassword = localStorage.getItem("password");

  //Compare the input values with the values stored in local storage
  if (username === storedUsername && password === storedPassword) {
    //Redirect to the profile page
    window.location.href = "profile.html";
  } else {
    //Display an error message
    alert("Invalid username or password");
  }
});
