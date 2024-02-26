if(!window.Amer){
  window.Amer={};
}
let themName='AmerTheme';
function startthemepage()
{
	var CurrentTheme=getPreferredTheme();
	setTheme(CurrentTheme)
}
function getPreferredTheme(){
  if(getStoredTheme()){
    return getStoredTheme();
  }
  return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
}
function getStoredTheme(){
  return localStorage.getItem(themName);
}
function setStoredTheme(theme){
  localStorage.setItem(themName, theme)
}
function setTheme(theme){
  if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    document.documentElement.setAttribute('data-bs-theme', 'dark')
  } else {
    document.querySelectorAll(`[data-bs-theme]`).forEach(element => {
      element.setAttribute('data-bs-theme', theme)
    });
    let dropdown=$('.dropdown');
    $.each(dropdown,function(k,v){
      $(v).addClass(theme);
      $(v).attr('data-bs-theme', theme);
    });
    $.each($('#AmerTable'),function(k,v){
      $(v).addClass("table-"+theme);
      $(v).attr('data-bs-theme', theme);
    });
    let html = document.querySelector('html');
    html.classList.add(theme);
    document.documentElement.setAttribute('data-bs-theme', theme)
  }
}
function showActiveTheme (theme, focus = false){
   themeSwitcher = document.querySelector('#bd-theme')
  if (!themeSwitcher) {
    return
  }
  //change menu
   btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
  document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
    element.classList.remove('active')
    element.classList.add('d-none');
    element.setAttribute('aria-pressed', 'false')
  })
  btnToActive.classList.add('active')
  btnToActive.setAttribute('aria-pressed', 'true')
  btnToActive.classList.remove('d-none');
  themeSwitcher.focus()
}
document.querySelector('#bd-theme').parentElement.addEventListener('click',()=>{
   CurrentTheme=getPreferredTheme();
  if(CurrentTheme == 'dark'){
    CurrentTheme='light';
  }else{  CurrentTheme='dark';}
  showActiveTheme(CurrentTheme)
  document.querySelectorAll('[data-bs-theme-value]')
    .forEach(toggle => {
      toggle.addEventListener('click', () => {
         theme = toggle.getAttribute('data-bs-theme-value')
        setStoredTheme(theme)
        showActiveTheme(theme, true)
      })
    })
});
window.addEventListener('DOMContentLoaded', () => {
  startthemepage();
  //setTheme
  showActiveTheme(getPreferredTheme())
  document.querySelectorAll('[data-bs-theme-value]')
    .forEach(toggle => {
      toggle.addEventListener('click', () => {
        theme = toggle.getAttribute('data-bs-theme-value')
        setStoredTheme(theme)
        setTheme(theme)
        showActiveTheme(theme, true)
      })
    })
})
