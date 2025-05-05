document.getElementById("bgColor").addEventListener("input", function () {
  document.querySelector(".red").style.backgroundColor = this.value;
});
function validate(event) {
  event.preventDefault(); // Prevent default form submission

  const name = document.getElementById("username").value.trim();
  const email = document.getElementById("email").value.trim();
  const genderMale = document.getElementById("male").checked;
  const genderFemale = document.getElementById("female").checked;
  const password = document.getElementById("password").value.trim();
  const confipassword = document.getElementById("confipassword").value.trim();
  const dob = document.getElementById("dob").value;
  const city = document.getElementById("city").value;
  const termsAgreed = document.getElementById("agreed").checked;
  const termsNotAgreed = document.getElementById("nagree").checked;

  const message = document.getElementById("message");

  // Check if any field is empty or unchecked
  if (
    name === "" ||
    email === "" ||
    (!genderMale && !genderFemale) ||
    password === "" ||
    confipassword === "" ||
    dob === "" ||
    city === "" ||
    (!termsAgreed && !termsNotAgreed)
  ) {
    message.style.color = "red";
    message.textContent = "Please fill out all fields before submitting.";
    return false;
  }
  //validate name
  const anyNumber = /\d/.test(name);
  if (anyNumber) {
    message.style.color = "red";
    message.innerHTML = "Name must no contain numbers";
    //alert("Name must not contain numbers.");
    return false;
  }
  //validate password
  if (password.length < 8 || confipassword.length < 8) {
    message.style.color = "red";
    message.innerHTML = "password must contain 8 character";
    return false;
  }
  if (password !== confipassword) {
    alert("password and confirmpassword are not same");
    return false;
  }

  //validate Email
  //regex for email validation
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailPattern.test(email)) {
    message.style.color = "red";
    message.textContent = "Please enter a valid email address.";
    return false;
  }

  //validate birthday
  const today = new Date();
  const birthdaydate = new Date(dob);
  let age = today.getFullYear() - birthdaydate.getFullYear();
  if (age < 18) {
    message.style.color = "red";
    message.innerHTML = "you must be at lest 18 years old";
    return false;
  }

  message.textContent = "";
  event.target.submit();
}
