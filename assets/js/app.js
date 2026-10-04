/*
 * شطبتكم — envoi AJAX des formulaires avec messages de validation en arabe ou en français.
 * Chaque formulaire .js-ajax-form est envoyé à son action et attend du JSON :
 *   { ok: true, message: "..." }  ou  { ok: false, message: "...", errors: { champ: "..." } }
 */
(function () {
  "use strict";

  var ALL_MESSAGES = {
    ar: {
      required: "هذا الحقل مطلوب.",
      email: "يرجى إدخال بريد إلكتروني صحيح.",
      tel: "يرجى إدخال رقم هاتف صحيح.",
      network: "تعذّر الاتصال بالخادم. حاول مرة أخرى أو راسلنا عبر واتساب.",
      sending: "جارٍ الإرسال...",
    },
    fr: {
      required: "Ce champ est obligatoire.",
      email: "Veuillez saisir une adresse e-mail valide.",
      tel: "Veuillez saisir un numéro de téléphone valide.",
      network: "Impossible de joindre le serveur. Réessayez ou écrivez-nous sur WhatsApp.",
      sending: "Envoi en cours…",
    },
  };
  var MESSAGES = ALL_MESSAGES[document.documentElement.lang] || ALL_MESSAGES.ar;

  function fieldMessage(field) {
    if (field.validity.valueMissing) return MESSAGES.required;
    if (field.validity.typeMismatch && field.type === "email") return MESSAGES.email;
    if (field.validity.patternMismatch && field.type === "tel") return MESSAGES.tel;
    return field.validationMessage;
  }

  function clearErrors(form) {
    form.querySelectorAll(".is-invalid").forEach(function (el) {
      el.classList.remove("is-invalid");
      el.removeAttribute("aria-invalid");
    });
    form.querySelectorAll(".cs_field_error").forEach(function (el) {
      el.remove();
    });
  }

  function showError(form, name, message) {
    var field = form.querySelector('[name="' + name + '"]');
    if (!field) return;
    field.classList.add("is-invalid");
    field.setAttribute("aria-invalid", "true");
    // newsletter input is inline with its button: the result line is enough
    if (form.classList.contains("cs_newsletter_form")) return;
    var error = document.createElement("span");
    error.className = "cs_field_error";
    error.textContent = message;
    var anchor = field.closest(".cs_radio_cards") || field;
    anchor.insertAdjacentElement("afterend", error);
  }

  function setResult(form, message, ok) {
    var result = form.querySelector(".cs_form_result");
    if (!result) return;
    result.textContent = message;
    result.classList.toggle("is-success", ok === true);
    result.classList.toggle("is-error", ok === false);
  }

  function validate(form) {
    var valid = true;
    var firstInvalid = null;
    form.querySelectorAll("input, select, textarea").forEach(function (field) {
      if (field.name === "website" || field.type === "hidden") return;
      if (field.type === "radio" && field !== form.querySelector('[name="' + field.name + '"]')) return;
      if (!field.checkValidity()) {
        valid = false;
        showError(form, field.name, fieldMessage(field));
        firstInvalid = firstInvalid || field;
      }
    });
    if (firstInvalid) firstInvalid.focus();
    return valid;
  }

  function handleSubmit(event) {
    var form = event.target;
    event.preventDefault();
    clearErrors(form);
    setResult(form, "");

    if (!validate(form)) {
      if (form.classList.contains("cs_newsletter_form")) {
        setResult(form, MESSAGES.email, false);
      }
      return;
    }

    var button = form.querySelector('[type="submit"]');
    if (button) button.disabled = true;
    setResult(form, MESSAGES.sending);

    fetch(form.action, {
      method: "POST",
      body: new FormData(form),
      headers: { Accept: "application/json" },
      credentials: "same-origin",
    })
      .then(function (response) {
        return response.json().catch(function () {
          throw new Error("invalid-json");
        });
      })
      .then(function (data) {
        if (data.ok) {
          setResult(form, data.message, true);
          form.reset();
          return;
        }
        Object.keys(data.errors || {}).forEach(function (name) {
          showError(form, name, data.errors[name]);
        });
        setResult(form, data.message || MESSAGES.network, false);
      })
      .catch(function () {
        setResult(form, MESSAGES.network, false);
      })
      .finally(function () {
        if (button) button.disabled = false;
      });
  }

  document.querySelectorAll(".js-ajax-form").forEach(function (form) {
    form.setAttribute("novalidate", "");
    form.addEventListener("submit", handleSubmit);
    form.addEventListener("input", function (event) {
      var field = event.target;
      // errors of a radio group are attached to its first radio
      if (field.type === "radio") field = form.querySelector('[name="' + field.name + '"]');
      if (field.classList.contains("is-invalid") && field.checkValidity()) {
        field.classList.remove("is-invalid");
        field.removeAttribute("aria-invalid");
        var error = (field.closest(".cs_radio_cards") || field).nextElementSibling;
        if (error && error.classList.contains("cs_field_error")) error.remove();
      }
    });
  });

  // Step boxes and FAQ heads are clickable in main.js: make them keyboard-accessible too
  document.querySelectorAll(".cs_iconbox_style_2[role='button'], .cs_accordian_head[role='button']").forEach(function (box) {
    box.addEventListener("keydown", function (event) {
      if (event.key === "Enter" || event.key === " ") {
        event.preventDefault();
        box.click();
      }
    });
  });

  // Pre-select a service passed as ?service=slug (from service cards / details page)
  var params = new URLSearchParams(window.location.search);
  var preset = params.get("service");
  if (preset) {
    var select = document.querySelector('select[name="service"]');
    if (select && select.querySelector('option[value="' + CSS.escape(preset) + '"]')) {
      select.value = preset;
    }
  }

  // Booking date cannot be in the past
  var dateInput = document.querySelector('input[name="preferred_date"]');
  if (dateInput) {
    var today = new Date();
    today.setMinutes(today.getMinutes() - today.getTimezoneOffset());
    dateInput.min = today.toISOString().slice(0, 10);
  }
})();
