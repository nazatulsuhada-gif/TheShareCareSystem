function applyTheme(theme) {
  if (theme === "dark") {
    document.body.classList.add("dark-mode");
  } else {
    document.body.classList.remove("dark-mode");
  }
}

window.addEventListener("DOMContentLoaded", () => {
  const theme = localStorage.getItem("theme") || "light";
  applyTheme(theme);
});

function toggleTheme() {
  const current = localStorage.getItem("theme") || "light";
  const newTheme = current === "dark" ? "light" : "dark";

  localStorage.setItem("theme", newTheme);
  applyTheme(newTheme);
}

