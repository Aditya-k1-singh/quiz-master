/* =========================
   OPTION SELECTION (QUIZ)
   ========================= */

document.addEventListener("click", function (e) {
  if (e.target.classList.contains("option")) {
    // Remove active class from all options
    document.querySelectorAll(".option").forEach(opt => {
      opt.classList.remove("active");
    });

    // Mark selected option
    e.target.classList.add("active");

    // Store selected answer in hidden input
    const answerInput = document.getElementById("answer");
    if (answerInput) {
      answerInput.value = e.target.dataset.value;
    }
  }
});


/* =========================
   QUIZ TIMER
   ========================= */

let defaultTime = 10; // seconds
let timeLeft = defaultTime;
let timerInterval = null;

function startTimer() {
  const timerEl = document.getElementById("time");
  const form = document.getElementById("quizForm");

  if (!timerEl || !form) return;

  // Reset any existing timer
  if (timerInterval) {
    clearInterval(timerInterval);
    timerInterval = null;
  }

  // Initialize timeLeft from DOM if present, otherwise use default
  const parsed = parseInt(timerEl.textContent, 10);
  timeLeft = Number.isFinite(parsed) ? parsed : defaultTime;
  timerEl.textContent = timeLeft;

  timerInterval = setInterval(() => {
    timerEl.textContent = timeLeft;

    if (timeLeft <= 0) {
      clearInterval(timerInterval);
      timerInterval = null;
      // ensure form submits only once
      try {
        form.submit(); // auto submit quiz
      } catch (e) {
        console.error('Auto-submit failed', e);
      }
    }

    timeLeft--;
  }, 1000);
}


/* =========================
   PROGRESS BAR
   ========================= */

function updateProgress(current, total) {
  const bar = document.getElementById("progress-bar");
  if (!bar) return;

  const percent = Math.round((current / total) * 100);
  bar.style.width = percent + "%";
}


/* =========================
   AUTO START FEATURES
   ========================= */

document.addEventListener("DOMContentLoaded", function () {
  // Start timer if quiz page
  if (document.getElementById("time")) {
    startTimer();
  }
});