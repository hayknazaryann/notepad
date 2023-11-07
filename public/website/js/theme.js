//elements
const inputCheckbox = window.document.querySelector('.switcher-input');
const documentBody = document.body;

//events
inputCheckbox.addEventListener('change', () => {
    let theme = getTheme();

    if(theme == 'dark') {
        setTheme('light');
    } else {
        setTheme('dark');
    }
});


//functions
function changeBackground () {
    let theme = getTheme();

    if(theme == 'dark') {
        documentBody.setAttribute('data-theme', 'dark');
    } else {
        documentBody.removeAttribute('data-theme');
    }
}

function checkTheme() {
    let theme = getTheme();
    if(theme == null || theme == undefined || typeof(theme) != 'string') {
        setTheme('light');
    } else {
        if(theme == 'dark') {
            setTheme('dark');
        } else {
            setTheme('light');
        }
    }
}

function setTheme(theme = 'light') {
    window.localStorage.setItem('theme', theme);
    changeBackground();
}

function getTheme(){
    return window.localStorage.getItem('theme');
}

checkTheme();
