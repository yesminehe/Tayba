document.addEventListener("DOMContentLoaded", function () {
  const contactForm = document.getElementById("contactForm");
  const formSuccess = document.getElementById("formSuccess");

  const newForm = contactForm.cloneNode(true);
  contactForm.parentNode.replaceChild(newForm, contactForm);
  const freshForm = document.getElementById("contactForm");

  freshForm.addEventListener(
    "submit",
    function (e) {
      e.preventDefault();
      e.stopPropagation();

      clearErrors();
      formSuccess.textContent = "";
      formSuccess.className = "form-success-message";

      const formData = {
        name: document.getElementById("name").value.trim(),
        email: document.getElementById("email").value.trim(),
        phone: document.getElementById("phone").value.trim(),
        message: document.getElementById("message").value.trim(),
      };

      console.log("Form submission intercepted", formData);

      const errors = validateForm(formData);

      if (Object.keys(errors).length === 0) {
        submitForm(formData);
      } else {
        displayErrors(errors);
      }
    },
    true
  );

  function validateForm(data) {
    const errors = {};

    if (!data.name) {
      errors.name = "Le nom est requis";
    } else if (data.name.length < 2) {
      errors.name = "Le nom doit contenir au moins 2 caractères";
    }

    if (!data.email) {
      errors.email = "L'email est requis";
    } else if (!isValidEmail(data.email)) {
      errors.email = "Veuillez entrer un email valide";
    }

    if (data.phone && !isValidPhone(data.phone)) {
      errors.phone = "Veuillez entrer un numéro de téléphone valide";
    }

    if (!data.message) {
      errors.message = "Le message est requis";
    } else if (data.message.length < 10) {
      errors.message = "Le message doit contenir au moins 10 caractères";
    }

    return errors;
  }

  function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
  }

  function isValidPhone(phone) {
    const re = /^[0-9\s+()\-]+$/;
    return re.test(phone);
  }

  function displayErrors(errors) {
    for (const field in errors) {
      const input = document.getElementById(field);
      if (input) {
        input.classList.add("error");
        let errorElement = input.nextElementSibling;
        if (
          !errorElement ||
          !errorElement.classList.contains("error-message")
        ) {
          errorElement = document.createElement("div");
          errorElement.className = "error-message";
          input.parentNode.insertBefore(errorElement, input.nextSibling);
        }
        errorElement.textContent = errors[field];
      }
    }
  }

  function clearErrors() {
    const errorInputs = document.querySelectorAll(
      ".form-group input, .form-group textarea"
    );
    errorInputs.forEach((input) => {
      input.classList.remove("error");
      const errorMessage = input.nextElementSibling;
      if (errorMessage && errorMessage.classList.contains("error-message")) {
        errorMessage.remove();
      }
    });
  }

  function submitForm(formData) {
    console.log("Submitting form...", formData);

    const submitButton = document.querySelector(
      '#contactForm button[type="submit"]'
    );
    const originalButtonText = submitButton.textContent;
    submitButton.disabled = true;
    submitButton.textContent = "Envoi en cours...";

    fetch("send_email.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams(formData).toString(),
    })
      .then(async (response) => {
        console.log("Response status:", response.status);
        const data = await response.json().catch(() => {
          throw new Error("Invalid JSON response from server");
        });
        console.log("Response data:", data);

        if (!response.ok) {
          const error = new Error(data.message || "Une erreur est survenue");
          error.response = data;
          throw error;
        }
        return data;
      })
      .then((data) => {
        if (data.success) {
          formSuccess.textContent =
            data.message || "Merci ! Nous vous contacterons bientôt.";
          formSuccess.className = "form-success-message success";

          document.getElementById("contactForm").reset();
        } else {
          throw new Error(data.message || "Une erreur est survenue");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        formSuccess.textContent =
          error.message ||
          "Une erreur est survenue. Veuillez réessayer plus tard.";
        formSuccess.className = "form-success-message error";
      })
      .finally(() => {
        submitButton.disabled = false;
        submitButton.textContent = originalButtonText;
      });
  }
});
