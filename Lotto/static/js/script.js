document.addEventListener('DOMContentLoaded', () => {
   const currentPath = window.location.pathname.split('/').pop();
   const navItems = document.querySelectorAll('.nav-item');
   const links = {
     'index.php': 'Főoldal',
     'otos.php': 'Ötös lottó statisztika',
     'hatos.php': 'Hatos lottó statisztika'
   };
 
   navItems.forEach(navItem => {
      navItem.classList.remove('active');
   });
 
   if (links[currentPath]) {
      navItems.forEach(navItem => {
       if (navItem.textContent.trim() === links[currentPath]) {
         navItem.classList.add('active'); // Add hozzá az active osztályt a megfelelő linkhez
       }
     });
   }
 });
 