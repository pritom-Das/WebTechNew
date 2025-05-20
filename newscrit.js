document.getElementById("bgColor").addEventListener("input", function () {
  document.querySelector(".red").style.backgroundColor = this.value;
});

const form = document.getElementById("regform");
form.addEventListener("submit", function (event) {
  console.log("submit checked");
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

  // VALIDATION CHECKS — if anything is wrong:
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
    event.preventDefault(); // only prevent if invalid
    message.style.color = "red";
    message.textContent = "Please fill out all fields before submitting.";
    return false;
  }

  const anyNumber = /\d/.test(name);
  if (anyNumber) {
    event.preventDefault();
    message.style.color = "red";
    message.innerHTML = "Name must not contain numbers";
    return false;
  }

  if (password.length < 8 || confipassword.length < 8) {
    event.preventDefault();
    message.style.color = "red";
    message.innerHTML = "Password must contain 8 characters";
    return false;
  }

  if (password !== confipassword) {
    event.preventDefault();
    alert("Password and Confirm Password are not the same");
    return false;
  }

  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailPattern.test(email)) {
    event.preventDefault();
    message.style.color = "red";
    message.textContent = "Please enter a valid email address.";
    return false;
  }

  const today = new Date();
  const birthdaydate = new Date(dob);
  let age = today.getFullYear() - birthdaydate.getFullYear();
  if (age < 18) {
    event.preventDefault();
    message.style.color = "red";
    message.innerHTML = "You must be at least 18 years old";
    return false;
  }
  return true;
});
