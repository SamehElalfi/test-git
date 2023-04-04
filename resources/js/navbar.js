const navbar = document.querySelector("nav#navbar");
navbar.querySelector('#navbar-opener')?.addEventListener('click', toggleNavbar)
document.addEventListener('scroll', shrinkOnScroll);

/**
 * toggle the navbar when the user click on menu button
 * applied in small screens only
 */
function toggleNavbar(e) {
    const pagesMenu = navbar.querySelector('.pages-menu');
    const authLinks = navbar.querySelector('.auth-links');

    if (pagesMenu) {
        pagesMenu.classList.toggle('hidden');
        pagesMenu.classList.toggle('flex');
    }

    if (authLinks) {
        authLinks.classList.toggle('hidden');
        authLinks.classList.toggle('flex');
    }

    // remove `backdrop-blur-xl` and add it again to navbar
    // because when the size of an element is increased
    // and this element has a blur background effect
    // the new space (the increased area) doesn't get the same blur effect
    // so, we need to add the effect again manually.
    navbar.classList.toggle('backdrop-blur-xl')
    setTimeout(() => {
        navbar.classList.toggle('backdrop-blur-xl')
    }, 0)
}

/**
 * Shrink the navbar when the user scrolls down in the page
 * by removing the top & bottom padding `py-4`
 */
function shrinkOnScroll(e) {
    if (document.body.scrollTop > 350 || document.documentElement.scrollTop > 350) {
        navbar.classList.remove('py-4')
    } else {
        navbar.classList.add('py-4')
    }
}
