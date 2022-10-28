export const live = (eventType, className, cb) => {
  document.addEventListener(eventType, function (event) {
    let el = event.target;
    let found = el?.classList.contains(className);
    if(eventType == 'blur') {
      console.log(el);
    }
    while (el && !found) {
      el = el.parentElement;
      found = el?.classList.contains(className);
    }
    if (found) {
      cb(event, el);
    }
  });
};
