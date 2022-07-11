export const live = (eventType, className, cb) => {
  document.addEventListener(eventType, function (event) {
    let el = event.target;
    let found = false;
    while (el && !(found = el.classList.contains(className))) {

      el = el.parentElement;
    }

    if (found) {
      cb.call(el, event);
    }
  });
};
