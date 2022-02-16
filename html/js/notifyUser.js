//js functions to notify user.

function notify(str, wait, state) {
    const notifications = document.querySelector('#notifications');
    notifications.innerHTML = str;
    notifications.style.display = 'block';

    if (state === 'error'){
        notifications.classList.add('notification--error');
    } else if (state ==='success'){
        notifications.classList.add("notification--success")
    } else {
        const body = document.querySelector('body');
        if(body.classList.contains('isMobile')) {
            notifications.style.color = '#3a87ad';
            notifications.style.fontWeight = 'normal';
            notifications.style.backgroundColor = '#d9edf7';
        } else {
            notifications.style.color = 'black';
            notifications.style.fontWeight = 'normal';
            notifications.style.backgroundColor = 'white';
        }
     
    }

    if (wait === true) {
        const p = document.createElement('p');
        p.innerHTML  = '<a href="">&times;</a>';
        notifications.appendChild(p)
        const link = notifications.querySelector('a');
        link.addEventListener('click', (event)=> {
            event.preventDefault();
            fadeOutEffect(notifications)
        })

    } else {
        setTimeout(
            ()=> {
                notifications.classList = '';
                fadeOutEffect(notifications)

            }, 200
        )
    }
}


function fadeOutEffect(fadeTarget) {
    var fadeEffect = setInterval(function () {
        if (!fadeTarget.style.opacity) {
            fadeTarget.style.opacity = 1;
        }
        if (fadeTarget.style.opacity > 0) {
            fadeTarget.style.opacity -= 0.1;
        } else {
            clearInterval(fadeEffect);
        }
    }, 200);
}
