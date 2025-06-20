document.addEventListener("DOMContentLoaded", () => {
  const loginBtn = document.getElementById("openLogin");
  const loginModal = document.getElementById("loginModal");
  const closeLogin = document.getElementById("closeLogin");

  if (loginBtn && loginModal && closeLogin) {
    loginBtn.addEventListener("click", () => {
      loginModal.classList.remove("hidden");
    });

    closeLogin.addEventListener("click", () => {
      loginModal.classList.add("hidden");
    });

    window.addEventListener("click", (e) => {
      if (e.target === loginModal) {
        loginModal.classList.add("hidden");
      }
    });
  }
});
