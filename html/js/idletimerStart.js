let time;
let inactivityTime = function () {
  document.addEventListener('DOMContentLoaded', resetTimer);
  document.addEventListener('keypress', resetTimer);
  document.addEventListener('mousemove', resetTimer);
  function logout() {
    window.location = 'index.php?i=Logout.php';
  }
  function resetTimer() {
    clearTimeout(time);
    time = setTimeout(logout, 60 * 60 * 1000); // 60 minutes in miliseconds
  }
};
inactivityTime();
export const startIdletimeout = inactivityTime;
export const endIdleTimeout = () => clearTimeout(time);
