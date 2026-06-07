(function () {
  function applyTheme(theme) {
    document.body.classList.toggle("theme-dark", theme === "dark");
    var icon = document.querySelector("[data-theme-toggle] i");
    if (icon) {
      icon.className = theme === "dark" ? "fa fa-sun-o" : "fa fa-moon-o";
    }
  }

  document.addEventListener("DOMContentLoaded", function () {
    var savedTheme = localStorage.getItem("subRepositoryTheme") || "light";
    applyTheme(savedTheme);

    var themeButton = document.querySelector("[data-theme-toggle]");
    if (themeButton) {
      themeButton.addEventListener("click", function () {
        var nextTheme = document.body.classList.contains("theme-dark") ? "light" : "dark";
        localStorage.setItem("subRepositoryTheme", nextTheme);
        applyTheme(nextTheme);
      });
    }

    var backTop = document.querySelector("[data-back-top]");
    if (backTop) {
      backTop.addEventListener("click", function () {
        window.scrollTo({ top: 0, behavior: "smooth" });
      });
    }

    document.querySelectorAll(".file-input-wrap input[type='file']").forEach(function (fileInput) {
      fileInput.addEventListener("change", function () {
        var pathInput = fileInput.parentElement.querySelector("input.file-path");
        var names = Array.prototype.map.call(fileInput.files || [], function (file) {
          return file.name;
        });
        if (pathInput) {
          pathInput.value = names.join(", ");
        }
      });
    });

    if (window.jQuery) {
      $(".tooltipped").tooltip({ delay: 50 });
    }
  });
})();
